<?php
require_once 'controller.php';

class TeamController extends Controller
{
    public function get()
    {
        $results = $this->execute(
            "SELECT t.*, r.role_name
        FROM ioi_team_members t
        LEFT JOIN ioi_roles r ON t.role_id = r.role_id
        ORDER BY t.team_id ASC"
        );

        $this->send($results);
    }

    // public function get()
    // {
    //     $results = $this->execute("SELECT A.*, C.category_name, SC.sub_category_name, A.type_id, A.brand, T.type_name, 
    //    CO.asset_condition_name, A.status_id, S.status_name,A.file 
	// 	FROM itam_asset A
	// 	LEFT JOIN itam_asset_category C ON A.category_id = C.category_id
	// 	LEFT JOIN itam_asset_sub_category SC ON A.sub_category_id = SC.sub_category_id
	// 	LEFT JOIN itam_asset_type T ON A.type_id = T.type_id
	// 	LEFT JOIN itam_asset_condition CO ON A.asset_condition_id = CO.asset_condition_id
	// 	LEFT JOIN itam_asset_status S ON A.status_id = S.status_id;");

    //     $this->send($results);
    // }

    public function getOne()
    {
        $data = $this->getRecords(
            "ioi_team_members",
            ["team_id"],
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
        if (!empty($_FILES['file']['name'][0]) && $_FILES['file']['error'][0] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . "_" . basename($_FILES['file']['name'][0]);
            $filePath = "uploads/" . $fileName;

            move_uploaded_file($_FILES['file']['tmp_name'][0], $uploadDir . $fileName);
        }

        if (empty($employee_id)) $this->send(["message" => "Employee ID is required"], 400);
        if (empty($first_name)) $this->send(["message" => "First name is required"], 400);
        if (empty($last_name)) $this->send(["message" => "Last name is required"], 400);

        $team_id = $this->addRecords(
            "ioi_team_members",
            ["employee_id", "first_name", "last_name", "position", "quote", "role_id", "is_mancomm", "file"],
            [
                $employee_id,
                $first_name,
                $last_name,
                $position ?? "",
                $quote ?? "",
                $role_id,
                $is_mancomm,
                $filePath ?? "" 
            ]
        );

        if (!$team_id) {
            var_dump($this->db->errorInfo());
            die("Insert failed!");
        }

        return $this->send([
            "message" => "Team member created successfully",
            "id" => $team_id
        ]);
    }
}
