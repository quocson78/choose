<?php

class Default_ImagesController extends Choose_Controller_Action
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
