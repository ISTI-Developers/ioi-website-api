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



}



