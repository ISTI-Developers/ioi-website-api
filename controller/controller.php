<?php
require_once __DIR__ . "/../config/db.php";

class Controller
{
    public $connection;
    public $statement;
    public $isConnectionSuccess;
    public $connectionError;
    public function __construct()
    {
        $config = getDbConfig("DEV");
        try{
            $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['database'];
        
            $this->connection = new PDO($dsn, $config['username'], $config['password']);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->isConnectionSuccess = true;
        } catch (PDOException $e) {
            $this->connectionError = "<script defer> console.log('" . $e->getMessage() . "')</script>";
        }
    }

    public function setStatement($query)
    {
        if($this->isConnectionSuccess) {
            $this->statement = $this->connection->prepare($query);
        } else {
            $this->send(["error" => "Database connection failed"], 500);
        }
    }

    public function send($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo json_encode($data);
        exit;
    }


    public function execute($query, $params = [], $method = 'GET')
    {
        $this->setStatement($query);
        if($this->statement->execute($params)) {

        return match($method) {
            "GET" => $this->statement->fetchAll(),
            "POST" => $this->connection->lastInsertId(),
            default => $this->statement->rowCount() > 0,
        };

        }
        return false;
    }


    public function getRecords($table, $conditions = [], $conditionParams = [], $fetchType = "many", $columns = "*", $order = null) 
    {
        $cols = $columns !== "*" ? implode(", ", (array) $columns) : "*";

        $query = "SELECT {$cols} FROM {$table}";


        if(!empty($conditions)) {

        $whereParts = array_map(fn($col) => "{$col} = ?", $conditions);
        $query .= " WHERE " . implode(" AND ", $whereParts);

        }

        if($order !== null) {
            $query .= " {$order} ";
        }

        $results = $this->execute($query, $conditionParams);
        return $this->statement->rowCount() > 0 ? $fetchType === "one" ? $results[0] : $results : [];

    }


    public function addRecords($table, $columns = [], $values = [])
    {
        $col = "(" . implode(",", $columns) . ")";
        $placeholders = "(" . implode(",", array_fill(0, count($columns), "?")) . ")";
        $query = "INSERT INTO {$table} {$col} VALUES {$placeholders}";

        return $this->execute($query, $values, 'POST');
    }













}