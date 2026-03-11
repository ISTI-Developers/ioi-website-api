<?php

require_once 'baseimagecontroller.php';


class ProjectController extends BaseImageController {

    public function get() 
    {
        $data = $this->getRecords(
            "ioi_projects",
            [],
            [],
            "many",
            "*",
            "ORDER BY project_id ASC"
        );
        $this->send($data);
    }


   public function getOne($id)
    {
        $project = $this->getRecords("ioi_projects", ["project_id"], [$id], "one");
        if (!$project) {
            $this->send(["error" => "Project not found"], 404);
            return;
        }

        $points = $this->getRecords("ioi_projects_points", ["project_id"], [$id], "many");
        $prose = $this->getRecords("ioi_projects_prose", ["project_id"], [$id], "many"); 
        $project->points = $points;
        $project->prose = $prose; 
    

        $this->send($project);
    }   


    public function add()
    {
        $data = $this->getJsonInput();

        
        $this->validateRequired($data, [
            "project_name",
            "project_type",
            "start_date",
            "project_category",
            "file"
        ]);


        $start_date = date("Y-m-d", strtotime($data["start_date"]));
        $end_date = isset($data["end_date"]) && !empty($data["end_date"])
            ? date("Y-m-d", strtotime($data["end_date"]))
            : null; 

        $project_id = $this->addRecords(
            "ioi_projects",
            [
                "project_name",
                "project_type",
                "start_date",
                "end_date",
                "project_category",
                "company_description",
                "brand_positioning",
                "file"
            ],
            [
                $data["project_name"],
                $data["project_type"],
                $start_date,
                $end_date,
                $data["project_category"],
                $company_description,
                $brand_positioning,
                $data["file"]
            ]
        );

        
        $this->send([
            "message" => "Project created successfully",
            "id" => $project_id
        ], 201);
    }
}



