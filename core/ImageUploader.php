<?php

namespace Core;

class ImageUploader{
    public static function uploadProductImage($productId,$productSlug,$imageId,$imageFile) : array{
        $target_dir = "media/images/products/$productId/";
        $imageFileType = strtolower(pathinfo($imageFile["name"],PATHINFO_EXTENSION));
        $target_file = $target_dir . $productSlug . "-" . $imageId;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($imageFile["tmp_name"]);
        if($check === false) {
            throw new UploadException("Soubor není obrázek.");
        }
        
        if ($imageFile["size"] > 1000000) {
            throw new UploadException("Soubor je větší než 1MB.");
        }

        if($imageFileType != "jpg" && $imageFileType != "jpeg") {
            throw new UploadException("Jenom soubory JPG a JPEG jsou povoleny.");
        }

        if(!is_dir($target_dir)){
            mkdir($target_dir);
        }

        $defaultImagePath = $target_file . "-default." . $imageFileType;

        if (!move_uploaded_file($imageFile["tmp_name"], $defaultImagePath)) {
            throw new UploadException("Nastal neznámý problém při nahrávání.");
        }

        $im = imagecreatefromjpeg($defaultImagePath);
        $size = min(imagesx($im), imagesy($im));
        $offsetX = 0;
        $offsetY = 0;
        if($size == imagesy($im)){
            $offsetX = imagesx($im) / 2 - $size / 2;
        }else{
            if($size == imagesx($im)){
                $offsetY = imagesy($im) / 2 - $size / 2;
            }
        }
        
        $im2 = imagecrop($im, ['x' => $offsetX, 'y' => $offsetY, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagejpeg($im2, $defaultImagePath, 100);
            imagedestroy($im2);
        }
        imagedestroy($im);

        list($width, $height) = getimagesize($defaultImagePath);
        $new_width = 150;
        $new_height = 150;

        // Resample
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($defaultImagePath);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $target_file . "-small." . $imageFileType, 100);

        $new_width = 450;
        $new_height = 450;

        // Resample
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($defaultImagePath);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $target_file . "-large." . $imageFileType, 100);

        return ["/" . $target_file . "-default." . $imageFileType,"/" . $target_file . "-large." . $imageFileType,"/" . $target_file . "-small." . $imageFileType];
    }
}