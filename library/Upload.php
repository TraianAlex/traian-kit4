<?php
/**
 * The class has the following public methods:
• setMaxSize(): Takes an integer and sets the maximum size for each upload file, overriding the default 51200 bytes (50kB). The value must be expressed as bytes.
• getMaxSize(): Reports the maximum size in kB formatted to one decimal place.
• addPermittedTypes(): Takes an array of MIME types, and adds them to the types of file accepted for upload. A single MIME type can be passed as a string.
• setPermittedTypes(): Similar to addPermittedTypes(), but replaces existing values.
• move(): Saves the file(s) to the destination folder. Spaces in filenames are replaced by underscores. By default, files with the same name as an existing file are renamed by inserting a number in front of the filename extension. To overwrite files, pass true as an argument.
• getMessages(): Returns an array of messages reporting the status of uploads.
* default the class not overwrite and rename always if the same file is up..
*/
class Upload {

    protected $_filenames = array();
    protected $_uploaded = array();
    protected $_destination;
    protected $_max = 151200;
    protected $_messages = array();
    protected $_permitted = array('image/gif', 'image/jpeg', 'image/pjpeg',
                                 'image/png', 'image/x-png','image/tiff');
    protected $_renamed = false;

    public function __construct($path) {
        if (!is_dir($path) || !is_writable($path)) {
            throw new Exception("$path must be a valid, writable directory.");
        }
        $this->_destination = $path;
        $this->_uploaded = $_FILES;
    }

    public function move($overwrite = false) {
        $field = current($this->_uploaded);
        if (is_array($field['name'])) {
            foreach ($field['name'] as $number => $filename) {
                // process multiple upload
                $this->_renamed = false;
                $this->processFile($filename, $field['error'][$number],
                       $field['size'][$number], $field['type'][$number],
                       $field['tmp_name'][$number], $overwrite);
            }
        } else {
            $this->processFile($field['name'], $field['error'], $field['size'],
                               $field['type'], $field['tmp_name'], $overwrite);
        }
    }

    protected function processFile($filename, $error, $size, $type,$tmp_name,
                                                            $overwrite) {
        $OK = $this->checkError($filename, $error);
        if ($OK) {
            $sizeOK = $this->checkSize($filename, $size);
            $typeOK = $this->checkType($filename, $type);
            if ($sizeOK && $typeOK) {
                $name = $this->checkName($filename, $overwrite);
                $success = move_uploaded_file($tmp_name,
                                            $this->_destination . $name);
                if ($success) {
                    $this->_filenames[] = $name;
                    $message = "$filename uploaded successfully";
                    if ($this->_renamed) {
                        $message .= " and renamed $name";
                    }
                    $this->_messages[] = $message;
                    //throw new Exception("$message");
                } else {
                    //$this->_messages[] = "Could not upload $filename";
                    throw new Exception("Could not upload $filename");
                }
            }
        }
    }

    public function getFilenames() {
        return $this->_filenames;
    }

    public function getMessages() {
        return $this->_messages;
    }

    protected function checkError($filename, $error) {
        switch ($error) {
            case 0:
                return true;
            case 1:
            case 2:
                //$this->_messages[] = "$filename exceeds maximum size: " .
                        //$this->getMaxSize();
                throw new Exception("$filename exceeds maximum size: " .
                        $this->getMaxSize());
                return true;
            case 3:
                //$this->_messages[] = "Error uploading $filename. Please try again.";
                throw new Exception("Error uploading $filename. Please try again.");
                return false;
            case 4:
                //$this->_messages[] = 'No file selected.';
                throw new Exception('No file selected.');
                return false;
            default:
                //$this->_messages[] = "System error uploading $filename. Contact webmaster.";
                throw new Exception("System error uploading $filename. Contact
webmaster.");
                return false;
        }
    }

    protected function checkSize($filename, $size) {
        if ($size == 0) {
            return false;
        } elseif ($size > $this->_max) {
            //$this->_messages[] = "$filename exceeds maximum size: " .
              //      $this->getMaxSize();
            throw new Exception("$filename exceeds maximum size: " .
                    $this->getMaxSize());
            return false;
        } else {
            return true;
        }
    }

    protected function checkType($filename, $type) {
        if (empty($type)) {
            return false;
        } elseif (!in_array($type, $this->_permitted)) {
           //$this->_messages[] = "$filename is not a permitted type of file.";
            throw new Exception("$filename is not a permitted type of file.");
            return false;
        } else {
            return true;
        }
    }

    public function getMaxSize() {
        return number_format($this->_max / 1024, 1) . 'kB';
    }

    public function addPermittedTypes($types) {
        $types = (array) $types;
        $this->isValidMime($types);
        $this->_permitted = array_merge($this->_permitted, $types);
    }

    public function setPermittedTypes($types) {
        $types = (array) $types;
        $this->isValidMime($types);
        $this->_permitted = $types;
    }

    protected function isValidMime($types) {
        $alsoValid = array('video/quicktime','application/pdf',
                           'text/csv',      'application/rtf',
                          'text/plain',    'application/zip',
                         'text/rtf',      'application/sql',
                        'text/html',     'application/msword');
        $valid = array_merge($this->_permitted, $alsoValid);
        foreach ($types as $type) {
            if (!in_array($type, $valid)) {
                throw new Exception("$type is not a permitted MIME type");
            }
        }
    }

    public function setMaxSize($num) {
        if (!is_numeric($num)) {
            throw new Exception("Maximum size must be a number.");
        }
        $this->_max = (int) $num;
    }

    protected function checkName($name, $overwrite) {
        $nospaces = str_replace(' ', '_', $name);
        if ($nospaces != $name) {
            $this->_renamed = true;
        }
        if (!$overwrite) {
            // rename the file if it already exists
            $existing = scandir($this->_destination);
            if (in_array($nospaces, $existing)) {
                $dot = strrpos($nospaces, '.');
                if ($dot) {
                    $base = substr($nospaces, 0, $dot);
                    $extension = substr($nospaces, $dot);
                } else {
                    $base = $nospaces;
                    $extension = '';
                }
                $i = 1;
                do {
                    $nospaces = $base . '_' . $i++ . $extension;
                } while (in_array($nospaces, $existing));
                $this->_renamed = true;
            }
        }
        return $nospaces;
    }

}
//$extension = strtolower(substr($name, strpos($name, '.') + 1));