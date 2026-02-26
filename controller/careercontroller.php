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

    public function getOne($id) 
    {
        if(!$id) $this->send(["message" => "Career ID is required"], 400);

        $data = $this->getRecords(
            "ioi_careers",
            ["career_id"],
            [$id],
            "one"
        );

        $this->send($data);
    }

    public function add()
    {
        $data = json_decode($_POST['data'], true);
        extract($data);

        if(empty($career_title)) $this->send(["message" => "Career Title is required"], 400);
        if(empty($department)) $this->send(["message" => "Department is required"], 400);
        if(empty($work_setup)) $this->send(["message" => "Work Setup is required"], 400);
        if(empty($employment_type)) $this->send(["message" => "Employment Type is required"], 400);
        if(empty($description)) $this->send(["message" => "Description is required"], 400);

        $career_id = $this->addRecords(
            "ioi_careers",
            ["career_title", "department", "work_setup", "employment_type", "description", "is_active", "application_link"],
            [
                $career_title,
                $department,
                $work_setup,
                $employment_type,
                $description,
                $is_active ?? 1,
                $application_link ?? null
            ]
        );

        if(!$career_id) {
            $this->send(["message" => "Failed to create career"], 500);
        }

        return $this->send([
            "message" => "Career created successfully",
            "id" => $career_id
        ]);
    }

    public function update()
    {
        $data = json_decode($_POST['data'], true);
        extract($data);

        if(empty($career_id)) $this->send(["message" => "Career ID is required"], 400);
        if(empty($career_title)) $this->send(["message" => "Career Title is required"], 400);
        if(empty($department)) $this->send(["message" => "Department is required"], 400);
        if(empty($work_setup)) $this->send(["message" => "Work Setup is required"], 400);
        if(empty($employment_type)) $this->send(["message" => "Employment Type is required"], 400);
        if(empty($description)) $this->send(["message" => "Description is required"], 400);
    }

}