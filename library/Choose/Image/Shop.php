<?php

class Choose_Image_Shop extends Imagick
{
    static public function factory($params)
    {
        $ini = new Choose_Config_Ini('image.ini');

        $file = "{$ini->original_path}/images/{$params['round']}/{$params['shop_id']}/{$params['file']}";

        if (!file_exists($file)) {
            $file = $ini->image_not_found;
            $not_found = true;
        }

        $config = $ini->{$params['type']};

        $image = new Imagick($file);

        switch ($config->type) {
            case 'fit':
            default:
                $image->scaleImage(
                    (int)$config->width,
                    (int)$config->height,
                    true);
                break;
        }

        if (!$not_found) {
            $dest = APPLICATION_PATH . "/../public/{$params['full_path']}";
            mkdir(dirname($dest), 0777, ture);
            $image->writeImage($dest);
        }

        return $image;
    }
}