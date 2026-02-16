<?php
require_once 'controller.php';



class RolesController extends Controller {

    public function get() {

        $data = $this->getRecords(
            "ioi_roles",
            [],
            [],
            "many",
            "*",
            "ORDER BY role_id ASC"
        );

        $this->send($data);

        }


    public function getOne() {
        $data = $this->getRecords(
            "ioi_roles",
            ["role_id"],
            [$id],
            "one"
        );

        $this->send($data);
    }




}