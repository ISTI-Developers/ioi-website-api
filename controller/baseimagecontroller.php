<?php

require_once 'controller.php';

class BaseImageController extends Controller {

    protected function getJsonInput()
    {
        $input=json_decode(file_get_contents("php://input"),true);

        if(!$input) {
            $this->send(["message" => "Invalid JSON body"], 400);
        }
        return $input;
    }

    protected function validateRequired($data, $requiredFields)
    {
        foreach($requiredFields as $field) {
            if(empty($data[$field])) {
                $this->send(["message" => "$field is required"], 400);
            }
        }
    }
    protected function buildUpdateData($data, $allowedFields)
    {
        $updateFields = [];
        $updateValues = [];

        foreach($allowedFields as $field) {
            if(isset($data[$field])) {
                $updateFields[] = $field;
                $updateValues[] = $data[$field];
            }
        }

        return [$updateFields, $updateValues];
    }
    protected function handleUpdate($table, $idColumn, $allowedFields)
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "ID is required"], 400);
        }

        $input = $this->getJsonInput();
        [$updateFields, $updateValues] = $this->buildUpdateData($input, $allowedFields);

        if(empty($updateFields)) {
            $this->send(["message" => "No valid fields to update"], 400);
        }

        $this->updateRecords($table, $updateFields, $updateValues, $idColumn, $_GET['id']);
        $this->send(["message" => "Updated successfully"]);
    }

    protected function handleDelete($table, $idColumn)
    {
        if(!isset($_GET['id']) || $_GET['id'] === '') {
            $this->send(["message" => "ID is required"], 400);
        }

        $this->deleteRecords($table, $idColumn, $_GET['id']);
        $this->send(["message" => "Deleted successfully"]);
    }
}