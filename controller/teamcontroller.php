<?php

require_once 'baseimagecontroller.php';

class TeamController extends BaseImageController
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
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Team ID is required"], 400);
        }

        $data = $this->getRecords(
            "ioi_team_members",
            ["team_id"],
            [$_GET['id']],
            "one"
        );

        if(!$data) {
            $this->send(["message" => "Team member not found"], 404);
        }

        $this->send($data);
    }

    public function add()
    {
        $input = $this->getJsonInput();

        $this->validateRequired($input, [
            "employee_id",
            "first_name",
            "last_name",
            "position",
            "role_id",
            "file"
        ]);

        $team_id = $this->addRecords(
            "ioi_team_members",
            ["employee_id", "first_name", "last_name", "position", "is_mancomm", "quote", "role_id", "file"],
            [
                $input["employee_id"],
                $input["first_name"],
                $input["last_name"],
                $input["position"],
                $input["is_mancomm"] ?? 0,
                $input["quote"] ?? "",
                $input["role_id"],
                $input["file"]
            ]
        );

        $this->send([
            "message" => "Team member created successfully",
            "team_id" => $team_id
        ], 201);
    }
    public function update()
    {
        $this->handleUpdate(
            "ioi_team_members",
            "team_id",
            ["employee_id", "first_name", "last_name", "position", "is_mancomm", "quote", "role_id", "file"]
        );
    }

    public function delete()
    {
        $this->handleDelete("ioi_team_members", "team_id");
    }
}
