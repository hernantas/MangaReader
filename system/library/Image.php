<?php
    namespace Library;

    class Image
    {
        private $savePath = 'public/img_cache/';

        public function getContent($path)
        {
            $info = $this->getImageData($path);
            $name = md5($path);

            if (file_exists(BASE_PATH.$this->savePath.$name.'.'.$info['typeString']))
            {
                return baseUrl().$this->savePath.$name.'.'.$info['typeString'];
            }

            $image = $this->createNewImage($info['type'], $path);
            $this->output($info['type'], $image, BASE_PATH.$this->savePath.$name.'.'.$info['typeString']);
            imagedestroy($image);
            return baseUrl().$this->savePath.$name.'.'.$info['typeString'];
        }

        public function getContent64($path)
        {
            $info = $this->getImageData($path);
            $image = $this->createNewImage($info['type'], $path);
            return [
                'img'=>$this->output64($info['type'], $image),
                'width'=>$info['width'],
                'height'=>$info['height']
            ];
        }

        public function getContentCrop($path, $width, $height)
        {
            $info = $this->getImageData($path);
            $name = md5($path . $width . $height . 'crop');

            if (file_exists(BASE_PATH.$this->savePath.$name.'.'.$info['typeString']))
            {
                return baseUrl().$this->savePath.$name.'.'.$info['typeString'];
            }

            // Generate New Image
            $oldWidth = $info['width'];
            $oldHeight = $info['height'];
            $ratio = ($oldWidth < $oldHeight) ?
                ($width / $oldWidth) : ($height / $oldHeight);

            $newImage = imagecreatetruecolor($width, $height);
            $image = $this->createNewImage($info['type'], $path);

            imagecopyresampled($newImage, $image, 0, 0, 0, 0,
                $oldWidth * $ratio, $oldHeight * $ratio, $oldWidth, $oldHeight);
            // imagecopy($cropedImage, $resizedImage, 0, 0, $width, $height, $oldWidth * $ratio, $oldHeight * $ratio);

            $this->output($info['type'], $newImage, BASE_PATH.$this->savePath.$name.'.'.$info['typeString']);
            imagedestroy($newImage);
            imagedestroy($image);
            return baseUrl().$this->savePath.$name.'.'.$info['typeString'];
        }

        private function getImageData($path)
        {
            list($width, $height, $type) = getimagesize($path);
            $typestring = '';
            if ($type == IMAGETYPE_JPEG)
            {
                $typeString = 'jpg';
            }
        	elseif ($type == IMAGETYPE_PNG)
            {
                $typeString = 'png';
            }
        	elseif ($type == IMAGETYPE_GIF)
            {
                $typeString = 'gif';
            }
            return [
                'width'=>$width,
                'height'=>$height,
                'type'=>$type,
                'typeString'=>$typeString
            ];
        }

        private function createNewImage($type, $path)
        {
            $image = null;
            switch ($type)
            {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($path);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($path);
                    imagealphablending($image, true);
                	imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($path);
                    break;
            }
            return $image;
        }

        private function output($type, $img, $output)
        {
            switch ($type)
            {
                case IMAGETYPE_JPEG:
                    imagejpeg($img, $output);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($img, $output);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($img, $output);
                    break;
            }
        }

        private function output64($type, $img)
        {
            ob_start();
            switch ($type)
            {
                case IMAGETYPE_JPEG:
                    imagejpeg($img);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($img);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($img);
                    break;
            }
            $output = ob_get_contents();
            ob_end_clean();
            return 'data:image/' . $type . ';base64,' . base64_encode($output);
        }
    }

?>
