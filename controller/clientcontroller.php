<?php


require_once 'controller.php';


class ClientController extends Controller {

    public function get() 
    {
        $data = $this->getRecords(
            "ioi_clients",
            [],
            [],
            "many",
            "*",
            "ORDER BY client_id ASC"

        );
        $this->send($data);
    }


    public function getOne() 
    {
        $data = $this->getRecords(
            "ioi_clients",
            ["client_id"],
            [$id],
            "one"
        );

        $this->send($data);
    }



    public function add()
    {
        $data = json_decode($_POST['data'], true);
        extract($data);

        $filePath = null;
        if(!empty($_FILES['file']['name'][0]) && $_FILES['file']['error'][0] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['file']['name'][0]);
        $filePath = "uploads/" . $fileName;


        move_uploaded_file($_FILES['file']['tmp_name'][0], $uploadDir . $fileName);
        }

        if(empty($client_name)) $this->send(["message" => "Client Name is required"], 400);
        if(empty($client_description)) $this->send(["message" => "Client Description is required"], 400);


        $client_id = $this->addRecords(
            "ioi_clients",
            ["client_name", "client_description", "file"],
            [
                $client_name,
                $client_description,
                $filePath ?? ""
            ]
        );

        if(!$client_id) {
            var_dump($this->db->errorInfo());
            die("Insert failed");
        }

        return $this->send([
            "message" => "Client created successfully",
            "id" => $client_id
        ]);
    }

}

