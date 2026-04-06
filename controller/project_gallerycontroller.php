<?php

require_once 'baseimagecontroller.php';


class ProjectGalleryController extends BaseImageController {

    public function get()
    {
        $data = $this->getRecords (
         "ioi_projects_gallery",
         [],
         [],
         "*",
         "many"
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

        $gallery = $this->getRecords("ioi_projects_gallery", ["project_id"], [$id], "many");

        $project->gallery = $gallery;

        $this->send($project);
    }
        
  public function add()
    {
        $data = $this->getJsonInput();

        $this->validateRequired($data, [
            "project_id",
            "file",
        ]);

        $result = $this->execute(
            "SELECT COALESCE(MAX(position), 0) + 1 AS next_position FROM ioi_projects_gallery WHERE project_id = ?",
            [$data["project_id"]]
        );

        $position = $result[0]->next_position;

        $gallery_id = $this->addRecords(
            "ioi_projects_gallery",
            [ "project_id",   "file",  "position"],
            [
                $data["project_id"],
                $data["file"],
                $position   
            ]
        );

        $this->send([
            "message" => "Images for gallery created successfully",
            "gallery_id" => $gallery_id
        ], 201);
    }
    }