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
}