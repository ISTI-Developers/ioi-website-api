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

        $results = array_map(fn($row) => (array) $row, $results); 

        foreach ($results as &$row) {
            $row['file'] = !empty($row['file'])
                ? explode(',', $row['file']) 
                : [];
        }

        $this->send($results);
    }

    public function getOne()
    {
        if (!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Team ID is required"], 400);
        }

        $data = $this->getRecords(
            "ioi_team_members",
            ["team_id"],
            [$_GET['id']],
            "one"
        );

        if (!$data) {
            $this->send(["message" => "Team member not found"], 404);
        }

        $data = (array) $data; 

        $data['file'] = !empty($data['file'])
            ? explode(',', $data['file']) 
            : [];

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

        
        $file = is_array($input["file"])
            ? implode(',', $input["file"]) 
            : $input["file"];

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
                $file 
            ]
        );

        $this->send([
            "message" => "Team member created successfully",
            "team_id" => $team_id
        ], 201);
    }

    public function update()
    {
        if (!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "ID is required"], 400);
        }

        $input = $this->getJsonInput(); 

        if (isset($input["file"]) && is_array($input["file"])) {
            $input["file"] = implode(',', $input["file"]); 
        }
        
        $allowedFields = ["employee_id", "first_name", "last_name", "position", "is_mancomm", "quote", "role_id", "file"];
        [$updateFields, $updateValues] = $this->buildUpdateData($input, $allowedFields);

        if (empty($updateFields)) {
            $this->send(["message" => "No valid fields to update"], 400);
        }

        $this->updateRecords("ioi_team_members", $updateFields, $updateValues, "team_id", $_GET['id']);
        $this->send(["message" => "Updated successfully"]);
    }

    public function delete()
    {
        $this->handleDelete("ioi_team_members", "team_id");
    }
}