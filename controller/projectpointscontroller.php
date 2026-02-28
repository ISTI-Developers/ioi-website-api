<?php


require_once 'controller.php';


class ProjectPointsController extends controller {

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

    public function add()
    {
          $data = json_decode($_POST['data'], true);
        extract($data);


        if(empty($project_id) || empty($type) || empty($content)){
            $this->send(["error" => "Missing required fields"], 404);
            return;
        }

        $point_id = $this->addRecords (
            "ioi_projects_points",
            ["project_id", "type", "content"],
            [$project_id, $type, $content]
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

}