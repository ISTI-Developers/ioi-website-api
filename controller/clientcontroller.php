<?php

require_once 'baseimagecontroller.php';


class ClientController extends BaseImageController {

    public function get() 
    {
        $data = $this->getRecords(
            "ioi_clients",
            [],
            [],
            "many",
            "*",
            "ORDER BY client_id ASC"

        );
        $this->send($data);
    }


    public function getOne() 
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "Client ID is required"], 400);
        }

        $data = $this->getRecords(
            "ioi_clients",
            ["client_id"],
            [$_GET[$id]],
            "one"
        );

        if(!$data) {
            $this->send(["message" => "Client not found"], 404);
        }

        $this->send($data);
    }



    public function add()
    {
        $input = $this->getJsonInput();
        $this->validateRequired($input, [
            "client_name",
            "client_description",
            "file"
        ]);

        $client_id = $this->addRecords(
            "ioi_clients",
            ["client_name", "client_description", "file"],
            [
                $input["client_name"],
                $input["client_description"],
                $input["file"]
            ]
        );

        $this->send([
            "message" => "Client created successfully",
            "id" => $client_id
        ], 201);
    }

    public function update()
    {
        $this->handleUpdate(
            "ioi_clients",
            "client_id",
            ["client_name", "client_description", "file"]
        );
    }

public function delete()
    {
        $this->handleDelete("ioi_clients", "client_id");
    }

}

