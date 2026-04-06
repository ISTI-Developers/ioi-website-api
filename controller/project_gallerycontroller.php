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
            "layout_group",
            "columns",
            "column_ratio",
            "display_order",
            "file"
        ]);

        $gallery_id = $this->addRecords(
            "ioi_projects_gallery",
            [ "project_id",   "layout_group",   "columns", "column_ratio", "display_order", "file"],
            [
                $data["project_id"],
                $data["layout_group"],
                $data["columns"],
                $data["column_ratio"],
                $data["display_order"],
                $data["file"]            
            ]
        );

        $this->send([
            "message" => "Images for gallery created successfully",
            "id" => $gallery_id
        ], 201);
    }
    }