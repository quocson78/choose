<?php

class Admin_Index_ImagesController extends Choose_Controller_Action_Admin
{
    public function indexAction()
    {
        $params = $this->getAllParams();

        $image = Choose_Image_Shop::factory($params);

        header('Content-type: image/jpeg');
        echo $image->getimageblob();

        $image->clear();
        $image->destroy();

        exit;
    }
}
