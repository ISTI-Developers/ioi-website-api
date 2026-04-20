<?php

require_once 'basecontroller.php';


class ProjectVideoController extends BaseController {
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


    public function add()
    {
        $data = $this->getJsonInput();

        $this->validateRequired($data, [
            "project_id",
            "file"
        ]);


        $video_id = $this->addRecords(
            "ioi_projects_video",
            ["project_id", "file"],
            [
                $data["project_id"],
                $data["file"]
            ]

        );

        if(!$video_id) {
            $this->send(["error" => "Insert failed"], 500);
            return;
        }

        $this->send([
            "message" => "Video added successfully",
            "video_id" => $video_id
        ]);

    }

    public function update()
    {
        $this->handleUpdate(
        "ioi_projects_video",
        "video_id",
        ["file"]
        );
    }
}