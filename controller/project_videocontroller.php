<?php

require_once 'baseimagecontroller.php';


class ProjectVideoController extends BaseImageController {
    public function get()
    {
        $data = $this->getRecords(
            "ioi_projects_video",
            [],
            [],
            "many",
            "*",
            "ORDER BY video_id ASC"
        );
        $this->send($data);
    }

    public function getOne($id)
    {
        $project = $this->getRecords("ioi_projects", ["project_id"], [$id], "one");
        if(!$project){
            $this->send(["error" => "Project not found"], 404);
            return;
        }

        $video = $this->getRecords("ioi_projects_video", ["project_id"], [$id], "many");

        $project->video = $video;

        $this->send($project);
    }


  
}