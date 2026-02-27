<?php

require_once 'controller.php';

class BannerController extends Controller {
    public function get() 
    {
        $data = $this->getRecords(
            "ioi_banners",
            [],
            [],
            "many",
            "*",
            "ORDER BY banner_id ASC"
        );
        $this->send($data);
    }

    public function getOne($id) 
    {
        if(!$id) $this->send(["message" => "Banner ID is required"], 400);

        $data = $this->getRecords(
            "ioi_banners",
            ["banner_id"],
            [$id],
            "one"
        );

        $this->send($data);
    }

    public function add()
{
    $data = json_decode($_POST['data'], true);
    extract($data);

    if(empty($section)) $this->send(["message" => "Section is required"], 400);
    if(empty($year)) $this->send(["message" => "Year is required"], 400);
    if(empty($text)) $this->send(["message" => "Text is required"], 400);

    // handle file directly from $_FILES
    $filePath = null;
    if (!empty($_FILES['file']['name'][0]) && $_FILES['file']['error'][0] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['file']['name'][0]);
        $filePath = "uploads/" . $fileName;
        move_uploaded_file($_FILES['file']['tmp_name'][0], $uploadDir . $fileName);
    }

    $banner_id = $this->addRecords(
        "ioi_banners",
        ["section", "year", "text", "file"],
        [
            $section,
            $year,
            $text,
            $filePath ?? null,
        ]
    );

    if(!$banner_id) {
        $this->send(["message" => "Failed to create banner"], 500);
    }

    $this->send(["message" => "Banner created successfully", "banner_id" => $banner_id], 201);
}
}
