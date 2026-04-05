<?php

require_once 'baseimagecontroller.php';


class ProjectVideoController extends BaseImageController {
    public function get()
    {
        $data = $this->getRecords(
            "ioi_projects_video",
            [],
            [],
            "many",
            "*",
            "ORDER BY video_id ASC"
        );
        $this->send($data);
    }

    
}