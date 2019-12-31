<?php

class SonglistMediaUploader
{
	var $mediaName;
	var $mediaType;
	var $mediaSize;
	var $mediaTmpName;
	var $mediaError;

	var $uploadDir = '';

	var $allowedMimeTypes = array();
	var $allowedExtensions = array();

	var $maxFileSize = 3299999999;
	var $maxWidth;
	var $maxHeight;

	var $targetFileName;

	var $prefix;

	var $errors = array();

	var $savedDestination;

	var $savedFileName;

	/**
	 * Constructor
	 * 
	 * @param   string  $uploadDir
	 * @param   array   $allowedMimeTypes
	 * @param   int	 $maxFileSize
	 * @param   int	 $maxWidth
	 * @param   int	 $maxHeight
	 * @param   int	 $cmodvalue
	 * @param   array   $allowedExtensions
	 **/
	function SonglistMediaUploader($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth=null, $maxHeight=null, $allowedExtensions=null )
	{
		if (is_array($allowedMimeTypes)) {
			$this->allowedMimeTypes =& $allowedMimeTypes;
		}
		$this->uploadDir = $uploadDir.(substr($uploadDir, strlen($uploadDir)-1,1)!=DS?DS:'');
		if (is_dir($uploadDir)) {
			foreach(explode(DS, $uploadDir) as $folder) {
				$path .= DS . $folder;
				mkdir($path, 0777);
			}
		}
		$this->maxFileSize = intval($maxFileSize);
		if(isset($maxWidth)) {
			$this->maxWidth = intval($maxWidth);
		}
		if(isset($maxHeight)) {
			$this->maxHeight = intval($maxHeight);
		}
		if( isset( $allowedExtensions ) && is_array( $allowedExtensions ) ) {
			$this->allowedExtensions =& $allowedExtensions ;
		}
	}

	/**
	 * Fetch the uploaded file
	 * 
	 * @param   string  $media_name Name of the file field
	 * @param   int	 $index	  Index of the file (if more than one uploaded under that name)
	 * @return  bool
	 **/
	function fetchMedia($index_name, $index = null)
	{
	if (!isset($_FILES[$index_name])) {
		$this->setErrors('File not found');
		return false;
	} elseif (is_array($_FILES[$index_name]['name'][$index]) && isset($index)) {
			$this->mediaName = $_FILES[$index_name]['name'][$index];
			$this->mediaType = $_FILES[$index_name]['type'][$index];
			$this->mediaSize = $_FILES[$index_name]['size'][$index];
			$this->mediaTmpName = $_FILES[$index_name]['tmp_name'][$index];
			$this->mediaError = !empty($_FILES[$index_name]['error'][$index]) ? $_FILES[$index_name]['errir'][$index] : 0;
	} else {
			$this->mediaName = $_FILES[$index_name]['name'];
			$this->mediaType = $_FILES[$index_name]['type'];
			$this->mediaSize = $_FILES[$index_name]['size'];
			$this->mediaTmpName = $_FILES[$index_name]['tmp_name'];
			$this->mediaError = !empty($_FILES[$index_name]['error']) ? $_FILES[$index_name]['error'] : 0;
		}
		$this->errors = array();
		if (intval($this->mediaSize) < 0) {
			$this->setErrors('Invalid File Size');
			return false;
		}
		if ($this->mediaName == '') {
			$this->setErrors('Filename Is Empty');
			return false;
		}
		if ($this->mediaTmpName == 'none' || !is_uploaded_file($this->mediaTmpName) || $this->mediaSize == 0 ) {
			$this->setErrors('No file uploaded');
			return false;
		}
		if ($this->mediaError > 0) {
			$this->setErrors('Error occurred: Error #'.$this->mediaError);
			return false;
		}
		return true;
	}

	/**
	 * Set the target filename
	 * 
	 * @param   string  $value
	 **/
	function setTargetFileName($value){
		$this->targetFileName = strval(trim($value));
	}

	/**
	 * Set the prefix
	 * 
	 * @param   string  $value
	 **/
	function setPrefix($value){
		$this->prefix = strval(trim($value));
	}

	/**
	 * Get the uploaded filename
	 * 
	 * @return  string 
	 **/
	function getMediaName()
	{
		return $this->mediaName;
	}

	/**
	 * Get the type of the uploaded file
	 * 
	 * @return  string 
	 **/
	function getMediaType()
	{
		return $this->mediaType;
	}

	/**
	 * Get the size of the uploaded file
	 * 
	 * @return  int 
	 **/
	function getMediaSize()
	{
		return $this->mediaSize;
	}

	/**
	 * Get the temporary name that the uploaded file was stored under
	 * 
	 * @return  string 
	 **/
	function getMediaTmpName()
	{
		return $this->mediaTmpName;
	}

	/**
	 * Get the saved filename
	 * 
	 * @return  string 
	 **/
	function getSavedFileName(){
		return $this->savedFileName;
	}

	/**
	 * Get the destination the file is saved to
	 * 
	 * @return  string
	 **/
	function getSavedDestination(){
		return $this->savedDestination;
	}

	/**
	 * Check the file and copy it to the destination
	 * 
	 * @return  bool
	 **/
	function upload($chmod = 0644)
	{
		if ($this->uploadDir == '') {
			$this->setErrors('Upload directory not set');
			return false;
		}
		if (!is_dir($this->uploadDir)) {
			$this->setErrors('Failed opening directory: '.$this->uploadDir);
			return false;
		}
		if (!is_writeable($this->uploadDir)) {
			$this->setErrors('Failed opening directory with write permission: '.$this->uploadDir);
			return false;
		}
		if (!$this->checkMimeType()) {
			$this->setErrors('MIME type not allowed: '.$this->mediaType);
			return false;
		}
		if (!$this->checkExtension()) {
			$this->setErrors('Extension not allowed');
			return false;
		}
		if (!$this->checkMaxFileSize()) {
			$this->setErrors('File size too large: '.$this->mediaSize);
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
			$this->setErrors('Failed uploading file: '.$this->mediaName);
			return false;
		}
		return true;
	}

	/**
	 * Copy the file to its destination
	 * 
	 * @return  bool 
	 **/
	function _copyFile($chmod)
	{
		$matched = array();
		if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched)) {
			return false;
		}
		if (isset($this->targetFileName)) {
			$this->savedFileName = $this->targetFileName;
		} elseif (isset($this->prefix)) {
			$this->savedFileName = uniqid($this->prefix).'.'.strtolower($matched[1]);
		} else {
			$this->savedFileName = strtolower($this->mediaName);
		}
		$this->savedDestination = $this->uploadDir.'/'.$this->savedFileName;
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
	function checkMaxFileSize()
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
	function checkMaxWidth()
	{
		if (!isset($this->maxWidth)||$this->maxWidth<1) {
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
	function checkMaxHeight()
	{
		if (!isset($this->maxHeight)||$this->maxHeight<1) {
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
	function checkMimeType()
	{
		if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Is the file the right extension
	 * 
	 * @return  bool
	 **/
	function checkExtension()
	{
		$ext = substr( strrchr( $this->mediaName , '.' ) , 1 ) ;
		if( ! empty( $this->allowedExtensions ) && ! in_array( strtolower( $ext ) , $this->allowedExtensions ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Add an error
	 * 
	 * @param   string  $error
	 **/
	function setErrors($error)
	{
		$this->errors[] = trim($error);
	}

	/**
	 * Get generated errors
	 * 
	 * @param	bool	$ashtml Format using HTML?
	 * 
	 * @return	array|string	Array of array messages OR HTML string
	 */
	function &getErrors($ashtml = true)
	{
		if (!$ashtml) {
			return $this->errors;
		} else {
			$ret = '';
			if (count($this->errors) > 0) {
				$ret = '<h4>Errors Returned While Uploading</h4>';
				foreach ($this->errors as $error) {
					$ret .= $error.'<br />';
				}
			}
			return $ret;
		}
	}
}
?>