<?php
    namespace Library;

    class Image
    {
        /**
         * Path to temporary directory to save generated image
         *
         * @var string
         */
        private $savePath = 'public/img_cache/';

        /**
         * Send image data to the user browser. Becarefull when using this method
         * since if you send any data previous to this will cause image to be corrupted.
         *
         * @param  string $path Image
         */
        public function getContent($path)
        {
            $info = $this->getImageData($path);
            $image = $this->createNewImage($info['type'], $path);
            $this->output($info['type'], $image);
            imagedestroy($image);
        }

        /**
         * Get image content as base 64
         *
         * @param  string $path Image to load
         *
         * @return string       Base64 image content
         */
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

        /**
         * Create new image from original but cropped, save it to temp directory
         * and return the new cropped image path.
         *
         * @param  string $path   Image to load
         * @param  int    $width  Image width cropped size
         * @param  int    $height Image height cropped size
         *
         * @return string         New cropped image path
         */
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

            $this->outputFile($info['type'], $newImage, BASE_PATH.$this->savePath.$name.'.'.$info['typeString']);
            imagedestroy($newImage);
            imagedestroy($image);
            return baseUrl().$this->savePath.$name.'.'.$info['typeString'];
        }

        /**
         * Get image properties
         *
         * @param  string $path Image path
         *
         * @return string       Image resource
         */
        private function getImageData($path)
        {
            list($width, $height, $type) = getimagesize($path);

            $typeString = '';

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

        /**
         * Create image resource from actual image
         *
         * @param  string $type Image type
         * @param  string $path Image path to load
         *
         * @return resource     Image resource
         */
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

        /**
         * Send image data to the user browser.
         *
         * @param  int      $type Image type
         * @param  resource $img  Image resource
         */
        private function output($type, $img)
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

        /**
         * Create image file based on image data
         *
         * @param  int      $type   Image type
         * @param  resource $img    Image resource
         * @param  string   $output Output file name
         */
        private function outputFile($type, $img, $output)
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

        /**
         * Convert image to the base64 string
         *
         * @param  int      $type Image type
         * @param  resource $img  Image resource
         *
         * @return string         Base64 image data
         */
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
