<?php

require_once 'controller.php';


class ProjectGalleryController extends Controller {

    public function get()
    {
        $data = $this->getRecords (
         "ioi_projects_galley",
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
        $data = json_decode($_POST['data'], true);
        extract($data);

        $filePath = null;
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (is_array($_FILES['file']['name'])) {
            if (!empty($_FILES['file']['name'][0]) && $_FILES['file']['error'][0] === UPLOAD_ERR_OK) {
                $fileName = time() . "_" . basename($_FILES['file']['name'][0]);
                $filePath = "uploads/" . $fileName;
                move_uploaded_file($_FILES['file']['tmp_name'][0], $uploadDir . $fileName);
            }
        } else {
            if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . "_" . basename($_FILES['file']['name']);
                $filePath = "uploads/" . $fileName;
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName);
            }
        }

        $gallery_id = $this->addRecords(
            "ioi_projects_gallery",
            ["project_id", "layout_group", "columns", "display_order", "file"],
            [
                $project_id,
                $layout_group,
                $columns,
                $display_order,
                $filePath ?? ""
            ]
        );

        if (!$gallery_id) {
            $this->send(["error" => "Insert failed"], 500);
            return;
        }

        $this->send([
            "message" => "Successfully added gallery images",
            "gallery_id" => $gallery_id
        ]);
    }

}