<?php

require_once 'controller.php';

class CareerController extends Controller {

    public function get()
    {
        $data = $this->getRecords(
            "ioi_careers",
            [],
            [],
            "many",
            "*",
            "ORDER BY career_id ASC"
        );
        $this->send($data);
    }

    public function getOne()
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Career ID is required"], 400);
        }

        $data = $this->getRecords(
            "ioi_careers",
            ["career_id"],
            [$_GET['id']],
            "one"
        );

        if(!$data) {
            $this->send(["message" => "Career not found"], 404);
        }

        $this->send($data);
    }

    public function add()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if(!$input) $this->send(["message" => "Invalid JSON body"], 400);

        $required = ["career_title", "department", "work_setup", "employment_type", "description"];
        foreach($required as $field) {
            if(empty($input[$field])) $this->send(["message" => "$field is required"], 400);
        }

        $career_id = $this->addRecords(
            "ioi_careers",
            ["career_title", "department", "work_setup", "employment_type", "description", "is_active", "application_link"],
            [
                $input["career_title"],
                $input["department"],
                $input["work_setup"],
                $input["employment_type"],
                $input["description"],
                $input["is_active"] ?? 1,
                $input["application_link"] ?? null
            ]
        );

        if(!$career_id) {
            $this->send(["message" => "Failed to create career"], 500);
        }

        $this->send([
            "message" => "Career created successfully",
            "career_id" => $career_id
        ], 201);
    }

    public function update()
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Career ID is required"], 400);
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if(!$input) $this->send(["message" => "Invalid JSON body"], 400);

        $required = ["career_title", "department", "work_setup", "employment_type", "description"];
        foreach($required as $field) {
            if(empty($input[$field])) $this->send(["message" => "$field is required"], 400);
        }

        $this->updateRecords(
            "ioi_careers",
            ["career_title", "department", "work_setup", "employment_type", "description", "is_active", "application_link"],
            [
                $input["career_title"],
                $input["department"],
                $input["work_setup"],
                $input["employment_type"],
                $input["description"],
                $input["is_active"] ?? 1,
                $input["application_link"] ?? null
            ],
            "career_id",
            $_GET['id']
        );

        $this->send(["message" => "Career updated successfully"]);
    }

    public function delete()
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Career ID is required"], 400);
        }

        $this->deleteRecords("ioi_careers", "career_id", $_GET['id']);
        $this->send(["message" => "Career deleted successfully"]);
    }
}