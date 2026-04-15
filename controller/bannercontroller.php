<?php

require_once 'basecontroller.php';

class BannerController extends BaseController {

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

    public function getOne()
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Banner ID is required"], 400);
        }

        $data = $this->getRecords(
            "ioi_banners",
            ["banner_id"],
            [$_GET['id']],
            "one"
        );

        if(!$data) {
            $this->send(["message" => "Banner not found"], 404);
        }

        $this->send($data);
    }

    public function add()
    {
        $input = $this->getJsonInput();

        $this->validateRequired($input, [
            "section",
            "year",
            "text",
            "file" 
        ]);

        $banner_id = $this->addRecords(
            "ioi_banners",
            ["section", "year", "text", "file"],
            [
                $input["section"],
                $input["year"],
                $input["text"],
                $input["file"]
            ]
        );

        $this->send([
            "message" => "Banner created successfully",
            "banner_id" => $banner_id
        ], 201);
    }
    public function update()
    {
        $this->handleUpdate(
            "ioi_banners",
            "banner_id",
            ["section", "year", "text", "file"]
        );
    }

    public function delete()
    {
        $this->handleDelete("ioi_banners", "banner_id");
    }
}