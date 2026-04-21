<?php

require_once 'basecontroller.php';


class ProjectGalleryController extends BaseController {

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
            "SELECT position FROM ioi_Projects_gallery WHERE project_id = ? ORDER BY position ASC",
            [$data["project_id"]]
        );

        $positions = array_column($result, "position");
        $position = 1;

        foreach($positions as $pos) {
            if($pos >= $position) {
                $position = $pos + 1;
            }
            else break;
        }

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
  

    public function update()
    {
        $this->handleUpdate(
            "ioi_projects_gallery",
            "gallery_id",
            ["file", "position"],
        );
    }

    public function delete()
    {
        $this->handleDelete("ioi_projects_gallery", "gallery_id");
    }

}