<?php

/**
 * Class SonglistMediaUploader
 */
class SonglistMediaUploader
{
    public $mediaName;
    public $mediaType;
    public $mediaSize;
    public $mediaTmpName;
    public $mediaError;

    public $uploadDir = '';

    public $allowedMimeTypes  = [];
    public $allowedExtensions = [];

    public $maxFileSize = 3299999999;
    public $maxWidth;
    public $maxHeight;

    public $targetFileName;

    public $prefix;

    public $errors = [];

    public $savedDestination;

    public $savedFileName;

    /**
     * Constructor
     *
     * @param   string $uploadDir
     * @param   array  $allowedMimeTypes
     * @param   int    $maxFileSize
     * @param   int    $maxWidth
     * @param   int    $maxHeight
     * @param   array  $allowedExtensions
     * @internal param int $cmodvalue
     */
    public function __construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth = null, $maxHeight = null, $allowedExtensions = null)
    {
        if (is_array($allowedMimeTypes)) {
            $this->allowedMimeTypes = &$allowedMimeTypes;
        }
        $this->uploadDir = $uploadDir . (DS != mb_substr($uploadDir, mb_strlen($uploadDir) - 1, 1) ? DS : '');
        if (is_dir($uploadDir)) {
            foreach (explode(DS, $uploadDir) as $folder) {
                $path .= DS . $folder;
                mkdir($path, 0777);
            }
        }
        $this->maxFileSize = (int)$maxFileSize;
        if (isset($maxWidth)) {
            $this->maxWidth = (int)$maxWidth;
        }
        if (isset($maxHeight)) {
            $this->maxHeight = (int)$maxHeight;
        }
        if (isset($allowedExtensions) && is_array($allowedExtensions)) {
            $this->allowedExtensions = &$allowedExtensions;
        }
    }

    /**
     * Fetch the uploaded file
     *
     * @param       $index_name
     * @param   int $index Index of the file (if more than one uploaded under that name)
     * @return bool
     * @internal param string $media_name Name of the file field
     */
    public function fetchMedia($index_name, $index = null)
    {
        if (!isset($_FILES[$index_name])) {
            $this->setErrors('File not found');

            return false;
        } elseif (is_array($_FILES[$index_name]['name'][$index]) && isset($index)) {
            $this->mediaName    = $_FILES[$index_name]['name'][$index];
            $this->mediaType    = $_FILES[$index_name]['type'][$index];
            $this->mediaSize    = $_FILES[$index_name]['size'][$index];
            $this->mediaTmpName = $_FILES[$index_name]['tmp_name'][$index];
            $this->mediaError   = !empty($_FILES[$index_name]['error'][$index]) ? $_FILES[$index_name]['errir'][$index] : 0;
        } else {
            $this->mediaName    = $_FILES[$index_name]['name'];
            $this->mediaType    = $_FILES[$index_name]['type'];
            $this->mediaSize    = $_FILES[$index_name]['size'];
            $this->mediaTmpName = $_FILES[$index_name]['tmp_name'];
            $this->mediaError   = !empty($_FILES[$index_name]['error']) ? $_FILES[$index_name]['error'] : 0;
        }
        $this->errors = [];
        if ((int)$this->mediaSize < 0) {
            $this->setErrors('Invalid File Size');

            return false;
        }
        if ('' == $this->mediaName) {
            $this->setErrors('Filename Is Empty');

            return false;
        }
        if ('none' === $this->mediaTmpName || !is_uploaded_file($this->mediaTmpName) || 0 == $this->mediaSize) {
            $this->setErrors('No file uploaded');

            return false;
        }
        if ($this->mediaError > 0) {
            $this->setErrors('Error occurred: Error #' . $this->mediaError);

            return false;
        }

        return true;
    }

    /**
     * Set the target filename
     *
     * @param   string $value
     **/
    public function setTargetFileName($value)
    {
        $this->targetFileName = trim($value);
    }

    /**
     * Set the prefix
     *
     * @param   string $value
     **/
    public function setPrefix($value)
    {
        $this->prefix = trim($value);
    }

    /**
     * Get the uploaded filename
     *
     * @return  string
     **/
    public function getMediaName()
    {
        return $this->mediaName;
    }

    /**
     * Get the type of the uploaded file
     *
     * @return  string
     **/
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Get the size of the uploaded file
     *
     * @return  int
     **/
    public function getMediaSize()
    {
        return $this->mediaSize;
    }

    /**
     * Get the temporary name that the uploaded file was stored under
     *
     * @return  string
     **/
    public function getMediaTmpName()
    {
        return $this->mediaTmpName;
    }

    /**
     * Get the saved filename
     *
     * @return  string
     **/
    public function getSavedFileName()
    {
        return $this->savedFileName;
    }

    /**
     * Get the destination the file is saved to
     *
     * @return  string
     **/
    public function getSavedDestination()
    {
        return $this->savedDestination;
    }

    /**
     * Check the file and copy it to the destination
     *
     * @param int $chmod
     * @return bool
     */
    public function upload($chmod = 0644)
    {
        if ('' == $this->uploadDir) {
            $this->setErrors('Upload directory not set');

            return false;
        }
        if (!is_dir($this->uploadDir)) {
            $this->setErrors('Failed opening directory: ' . $this->uploadDir);

            return false;
        }
        if (!is_writable($this->uploadDir)) {
            $this->setErrors('Failed opening directory with write permission: ' . $this->uploadDir);

            return false;
        }
        if (!$this->checkMimeType()) {
            $this->setErrors('MIME type not allowed: ' . $this->mediaType);

            return false;
        }
        if (!$this->checkExtension()) {
            $this->setErrors('Extension not allowed');

            return false;
        }
        if (!$this->checkMaxFileSize()) {
            $this->setErrors('File size too large: ' . $this->mediaSize);
        }
        if (!$this->checkMaxWidth()) {
            $this->setErrors(sprintf('File width must be smaller than %u', $this->maxWidth));
        }
        if (!$this->checkMaxHeight()) {
            $this->setErrors(sprintf('File height must be smaller than %u', $this->maxHeight));
        }
        if (count($this->errors) > 0) {
            return false;
        }
        if (!$this->_copyFile($chmod)) {
            $this->setErrors('Failed uploading file: ' . $this->mediaName);

            return false;
        }

        return true;
    }

    /**
     * Copy the file to its destination
     *
     * @param $chmod
     * @return bool
     */
    public function _copyFile($chmod)
    {
        $matched = [];
        if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched)) {
            return false;
        }
        if (isset($this->targetFileName)) {
            $this->savedFileName = $this->targetFileName;
        } elseif (isset($this->prefix)) {
            $this->savedFileName = uniqid($this->prefix, true) . '.' . mb_strtolower($matched[1]);
        } else {
            $this->savedFileName = mb_strtolower($this->mediaName);
        }
        $this->savedDestination = $this->uploadDir . '/' . $this->savedFileName;
        if (!move_uploaded_file($this->mediaTmpName, $this->savedDestination)) {
            return false;
        }
        @chmod($this->savedDestination, $chmod);

        return true;
    }

    /**
     * Is the file the right size?
     *
     * @return  bool
     **/
    public function checkMaxFileSize()
    {
        if ($this->mediaSize > $this->maxFileSize) {
            return false;
        }

        return true;
    }

    /**
     * Is the picture the right width?
     *
     * @return  bool
     **/
    public function checkMaxWidth()
    {
        if (!isset($this->maxWidth) || $this->maxWidth < 1) {
            return true;
        }
        if (false !== $dimension = getimagesize($this->mediaTmpName)) {
            if ($dimension[0] > $this->maxWidth) {
                return false;
            }
        } else {
            trigger_error(sprintf('Failed fetching image size of %s, skipping max width check..', $this->mediaTmpName), E_USER_WARNING);
        }

        return true;
    }

    /**
     * Is the picture the right height?
     *
     * @return  bool
     **/
    public function checkMaxHeight()
    {
        if (!isset($this->maxHeight) || $this->maxHeight < 1) {
            return true;
        }
        if (false !== $dimension = getimagesize($this->mediaTmpName)) {
            if ($dimension[1] > $this->maxHeight) {
                return false;
            }
        } else {
            trigger_error(sprintf('Failed fetching image size of %s, skipping max height check..', $this->mediaTmpName), E_USER_WARNING);
        }

        return true;
    }

    /**
     * Is the file the right Mime type
     *
     * (is there a right type of mime? ;-)
     *
     * @return  bool
     **/
    public function checkMimeType()
    {
        if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes)) {
            return false;
        }

        return true;
    }

    /**
     * Is the file the right extension
     *
     * @return  bool
     **/
    public function checkExtension()
    {
        $ext = mb_substr(mb_strrchr($this->mediaName, '.'), 1);
        if (!empty($this->allowedExtensions) && !in_array(mb_strtolower($ext), $this->allowedExtensions)) {
            return false;
        }

        return true;
    }

    /**
     * Add an error
     *
     * @param   string $error
     **/
    public function setErrors($error)
    {
        $this->errors[] = trim($error);
    }

    /**
     * Get generated errors
     *
     * @param   bool $ashtml Format using HTML?
     *
     * @return  array|string    Array of array messages OR HTML string
     */
    public function &getErrors($ashtml = true)
    {
        if (!$ashtml) {
            return $this->errors;
        }
        $ret = '';
        if (count($this->errors) > 0) {
            $ret = '<h4>Errors Returned While Uploading</h4>';
            foreach ($this->errors as $error) {
                $ret .= $error . '<br>';
            }
        }

        return $ret;
    }
}
