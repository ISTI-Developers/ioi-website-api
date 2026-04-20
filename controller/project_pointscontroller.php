<?php


require_once 'basecontroller.php';


class ProjectPointsController extends BaseController {

    public function get()
    {
        $data = $this->getRecords(
            "ioi_projects_points",
            [],
            [],
            "many",
            "*",
            "ORDER BY point_id ASC"
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

        $project->points = $points;

        $this->send($project);
    }   

    public function add($data = null)
    {

        $data = $data ?? $this->getJsonInput();
        extract($data);
        
        $this->validateRequired($data, [
            "project_id",
            "type",
            "content"
        ]);

        $point_id = $this->addRecords (
            "ioi_projects_points",
            ["project_id", "type", "content"],
            [
                $data["project_id"],
                $data["type"],
                $data["content"],
            ]
        );

        if(!$point_id) {
            $this->send(["error" => "Insert failed"], 500);
            return;
        }

        $this->send([
            "message" => "Successfully added project point",
            "point_id" => $point_id
        ]);
    }

    public function update($data = null)
    {
        $this->handleUpdate(
            "ioi_projects_points",
            "point_id",
            ["project_id", "content"],
            $data
        );
    }

    public function delete()
    {
        $this->handleDelete("ioi_projects_points", "point_id");
    }

}