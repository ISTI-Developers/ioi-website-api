<?php


require_once 'baseimagecontroller.php';

class ProjectProseController extends BaseImageController {

    public function get() 
    {
        $data = $this->getRecords(
            "ioi_projects_prose",
            [],
            [],
            "many",
            "*",
            "ORDER BY prose_id ASC"
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

    $prose = $this->getRecords("ioi_projects_prose", ["project_id"], [$id], "many");


    $project->prose = $prose;
    $this->send($project);
}

    public function add()
    {
        $data = $this->getJsonInput(); 
        extract($data);

        if (empty($project_id)) $this->send(["message" => "Project ID is required"], 400);
        if (empty($content)) $this->send(["message" => "Content is required"], 400);

        $prose_id = $this->addRecords(
            "ioi_projects_prose",
            ["project_id", "content"],
            [$project_id, $content]
        );

        if (!$prose_id) $this->send(["message" => "Failed to create Project Prose"], 500);

        return $this->send([
            "message" => "Prose created successfully",
            "id" => $prose_id
        ]);
    }


    }