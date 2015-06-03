<?php

class ImageHandler {

    public $save_dir; // The folder in which to save images
    public $max_dims;

    public function __construct($save_dir, $max_dims = array(350, 240)) {
        $this->save_dir = $save_dir; //Sets the $save_dir on instantiatio
        $this->max_dims = $max_dims;
    }

    /* Resizes/resamples an image uploaded via a web form
     * @param array $upload the array contained in $_FILES
     * @param bool $rename whether or not the image should be renamed
     * @return string the path to the resized uploaded file */

    public function processUploadedImage($file, $rename = TRUE) {
        
        // Separate the uploaded file array
        list($name, $type, $tmp, $err, $size) = array_values($file);
        // If an error occurred, throw an exception
        if ($err != UPLOAD_ERR_OK) {//4 error
            throw new Exception('An error occurred with the upload!');
            exit;
        }//we can check after max image size here or not
        $this->doImageResize($tmp); // Generate a resized image
        if ($rename === TRUE) {// Rename the file if the flag is set to TRUE
            //Retrieve information about the image
            $img_ext = $this->getImageExtension($type);
            $name = $this->renameFile($img_ext);
        }
        $this->checkSaveDir(); // Check that the directory exists
        //Create the full path to the image for saving
        $filepath = $this->save_dir . $name;
        // Store the absolute path to move the image
        $absolute = $_SERVER['DOCUMENT_ROOT'] . $filepath;
        if (!move_uploaded_file($tmp, $absolute)) {// Save the image
            throw new Exception("Couldn't save the uploaded file!");
        }
        return basename($filepath);
    }

    /* Ensures that the save directory exists
     * Checks for the existence of the supplied save directory,
     * and creates the directory if it doesn't exist. Creation is recursive.
     * @param void
     * @return void */

    private function checkSaveDir() {//return chmod($fullPath, 700)
    
        // Determines the path to check
        $path = $_SERVER['DOCUMENT_ROOT'] . $this->save_dir;
        if (!is_dir($path)) { // Checks if the directory exists
            if (!mkdir($path, 0777, TRUE)) {// Creates the directory
                // On failure, throws an error
                throw new Exception("Can't create the directory!");
            }
        }
    }

    /* Generates a unique name for a file
     * Uses the current timestamp and a randomly generated number
     * to create a unique name to be used for an uploaded file.
     * This helps prevent a new file upload from overwriting an existing file with the same name.
     * @param string $ext the file extension for the upload
     * @return string the new filename */

    private function renameFile($ext) {/* Returns the current timestamp and a random number to avoid duplicate filenames */
        date_default_timezone_set("America/Toronto");
        return time() . '_' . mt_rand(1000, 9999) . $ext;
    }

    /* Determines the filetype and extension of an image
     * @param string $type the MIME type of the image
     * @return string the extension to be used with the file */

    private function getImageExtension($type) {
        
        switch ($type) {
            case 'image/gif': return '.gif';
            case 'image/jpeg':
            case 'image/pjpeg': return '.jpg';
            case 'image/png': return '.png';
            default:
                throw new Exception('File type is not recognized!');
        }
    }

    /*     * Determines new dimensions for an image
      @param string $img the path to the upload
     * @return array the new and original image dimensions */

    private function getNewDims($img) {// Get new image dimensions
        
        list($src_w, $src_h) = getimagesize($img); // Assemble the necessary variables for processing
        list($max_w, $max_h) = $this->max_dims;
        if ($src_w > $max_w || $src_h > $max_h) {// Check that the image is bigger than the maximum dimensions
            $s = min($max_w / $src_w, $max_h / $src_h); // Determine the scale to which the image will be resized
        } else {
            /* If the image is smaller than the max dimensions, keep its dimensions by multiplying by 1 */
            $s = 1;
        }
        $new_w = round($src_w * $s); // Get the new dimensions
        $new_h = round($src_h * $s);
        return array($new_w, $new_h, $src_w, $src_h); // Return the new dimensions
    }

    /* Determines how to process images
      Uses the MIME type of the provided image to determine
     * what image handling functions should be used. This increases the perfomance of the script versus using
     * imagecreatefromstring().
     * @param string $img the path to the upload
     * @return array the image type-specific functions */

    private function getImageFunctions($img) {
        
        $info = getimagesize($img);
        switch ($info['mime']) {
            case 'image/jpeg':
            case 'image/pjpeg':
                return array('imagecreatefromjpeg', 'imagejpeg');
                break;
            case 'image/gif':
                return array('imagecreatefromgif', 'imagegif');
                break;
            case 'image/png':
                return array('imagecreatefrompng', 'imagepng');
                break;
            default:
                return FALSE;
                break;
        }
    }

    /*     * Generates a resampled and resized image
     * Creates and saves a new image based on the new dimensions and image type-specific functions determined by other
     * class methods.
     * @param array $img the path to the upload
     * @return void */

    private function doImageResize($img) {//private
        
        $d = $this->getNewDims($img); // Determine the new dimensions
// Determine what functions to use
        $funcs = $this->getImageFunctions($img);
// Create the image resources for resampling
        $src_img = $funcs[0]($img);
        $new_img = imagecreatetruecolor($d[0], $d[1]);
        if (imagecopyresampled($new_img, $src_img, 0, 0, 0, 0, $d[0], $d[1], $d[2], $d[3])) {
            imagedestroy($src_img);
            if ($new_img && $funcs[1]($new_img, $img)) {
                imagedestroy($new_img);
            } else {
                throw new Exception('Failed to save the new image!');
            }
        } else {
            throw new Exception('Could not resample the image!');
        }
    }

    public function processUploadedImage2() {

        $php_errors = array(1 => 'Maximum file size in php.ini exceeded',
            2 => 'Maximum file size in HTML form exceeded',
            3 => 'Only part of the file was uploaded',
            4 => 'No file was selected to upload.');
        ($_FILES['image']['error'] == 0) or
                handle_error2("the server couldn't upload the image you selected.",
                        $php_errors[$_FILES['image']['error']]);
        @is_uploaded_file($_FILES['image']['tmp_name']) or
                handle_error2("you were trying to do something naughty.",
                        "Uploaded request: file named {$_FILES['image']['tmp_name']}'");
        @getimagesize($_FILES['image']['tmp_name'])//like getImageExtens
                or handle_error2("you selected a file for your "
                        . "picture that isn't an image.", "
                   .{$_FILES['image']['tmp_name']} isn't a valid image file.");
        $this->doImageResize($_FILES['image']['tmp_name']);
        $now = time(); //rename file
        while (file_exists($name = $now . '-' . $_FILES['image']['name'])) {
            $now++;
        }
        $this->checkSaveDir(); // Check that the directory exists
//Create the full path to the img for saving
        $img_path = $this->save_dir . $name;
        $absolute = $_SERVER['DOCUMENT_ROOT'] . $img_path;
        @move_uploaded_file($_FILES['image']['tmp_name'], $absolute) or
                handle_error2("we had a problem saving your image to its permanent location.",
                        "permissions or related error moving file to {$upload_filename}");
        return $img_path;
    }

    public function processUploadedImage3($file) {//profiles pic
        //$this->doImageResize($_FILES['user_pic']['tmp_name']);
        $this->checkSaveDir();
        $img_path = $this->save_dir . $_SESSION['user'] . ".jpg";
        $saveto = $_SERVER['DOCUMENT_ROOT'] . $img_path;
        if (empty($file['tmp_name'])) {
            throw new Exception('&#x2718; Empty field');
        }
        if ($file['size'] > 2000000) {
            throw new Exception('&#x2718; The image size is too big(max 2MB)');
        }
        if (!@getimagesize($file['tmp_name'])) {
            throw new Exception('&#x2718; Select an image.');
        }
        if (!@move_uploaded_file($file['tmp_name'], $saveto)) {
            throw new Exception('&#x2718; We had a problem saving your image');
        }
        $typeok = TRUE;
        switch ($file['type']) {
            case "image/gif": $src = imagecreatefromgif($saveto);
                break;
            case "image/jpeg":  // Both regular and progressive jpegs
            case "image/pjpeg": $src = imagecreatefromjpeg($saveto);
                break;
            case "image/png": $src = imagecreatefrompng($saveto);
                break;
            default: $typeok = FALSE;
                break;
        }
        if ($typeok) {
            list($w, $h) = getimagesize($saveto);
            $max = 179; //100//@fix me image streched in height
            $tw = $w;
            $th = $h;
            if ($w > $h && $max < $w) {
                $th = $max / $w * $h;
                $tw = $max;
            } elseif ($h > $w && $max < $h) {
                $tw = $max / $h * $w;
                $th = $max;
            } elseif ($max < $w) {
                $tw = $th = $max;
            }
            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1),
                array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
            imagejpeg($tmp, $saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }
        return $img_path;
    }

    public function processUploadedImage4() {

        // Check for an uploaded file: name='upload'
        if (isset($_FILES['upload'])) {
            // Validate the type. Should be JPEG or PNG.
            $allowed = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/jpg',
                'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
            if (in_array($_FILES['upload']['type'], $allowed)) {
                // Move the file over.
                $saveto = $_SERVER['DOCUMENT_ROOT'] . SITE_ROOT . "/images/" .
                        $_FILES['upload']['name'];
                if (move_uploaded_file($_FILES['upload']['tmp_name'], $saveto)) {
                    echo '<p><em>The file has been uploaded!</em></p>';
                } // End of move... IF.
            } else { // Invalid type.
                echo '<p class="error">Please upload a JPEG or PNG image.</p>';
            }
        } // End of isset($_FILES['upload']) IF.
// Check for an error:
        if ($_FILES['upload']['error'] > 0) {
            echo '<p class="error">The file could not be uploaded '
            . 'because: <strong>';
// Print a message based upon the error.
            switch ($_FILES['upload']['error']) {
                case 1: print 'The file exceeds the upload_max_filesize '
                            . 'setting in php.';
                    break;
                case 2: print 'The file exceeds the MAX_FILE_SIZE '
                            . 'setting in the form.';
                    break;
                case 3: print 'The file was only partially uploaded.';
                    break;
                case 4: print 'No file was uploaded.';
                    break;
                case 6: print 'No temporary folder was available.';
                    break;
                case 7: print 'Unable to write to the disk.';
                    break;
                case 8: print 'File upload stopped.';
                    break;
                default: print 'A system error occurred.';
                    break;
            } // End of switch.
            print '</strong></p>';
        } // End of error IF.
        // Delete the file if it still exists:
        if (file_exists($_FILES['upload']['tmp_name']) &&
                is_file($_FILES['upload']['tmp_name'])) {
            unlink($_FILES['upload']['tmp_name']);
        }
    }

}
