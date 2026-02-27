<?php

require_once 'controller.php';


class ProjectController extends Controller {

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


    public function getOne()
    {
        $data = $this->getRecords(
            "ioi_projects",
            ["project_id"],
            [$id],
            "one"
        );

        $this->send($data);
    }


    public function add()
    {
        $data = json_decode($_POST['data'], true);
        extract($data);

        $filePath = null;
        if(!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/uploads/";
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);


            $fileName = time() . "_" . basename($_FILES['file']['name']);
            $filePath = "uploads/" . $fileName;

            move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName);
        
        }


        $project_id = $this->addProjects(
            "ioi_projects",
            ["project_name", "project_type", "start_date", "end_date", "project_category", "company_description", "brand_positioning", "file"],
            [
                $project_name,
                $project_type,
                $start_date,
                $end_date ?? null,
                $project_category,
                $company_description,
                $brand_positioning,
                $filePath ?? ""
            ]


        );

        if(!$project_id) {
            var_dump($this->db->errorInfo());
            die("Insert failed!");
        }

        return $this->send([
            "message" => "Project Detail created successfully",
            "id" => $project_id
        ]);
    }
}



