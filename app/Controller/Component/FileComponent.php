<?php

App::uses('Component', 'Controller');
/**
 * 
 * @author juraj
 *
 */
class FileComponent extends Component {

 	private $controller;
   	private $count = 0;
   	private $_allowed;
   	private $errors;
    	
    public function initialize(Controller $controller) {
		$this->controller = $controller;  
    }
	    
        	/*
                 * Function uploads file to destination folder
                 *
                 * @param array $file
                 * @param string $destination   
                 * @param string $name  optional
                 *
                 * @return 
                 */
                function upload ($file, $destination, $name = null, $allowed = NULL) {

                    //check if there is slash in the end of destination path string
                    $destination = $this->checkSlashInPath($destination);

                    if (is_dir($destination)){

                        if (isset($file) && is_array($file) && (strlen($file['tmp_name']) > 0) && !$this->upload_error($file['error'])) {

                            $tmp_name = $file['tmp_name'];
                            $regular_name = $file['name'];
                            if (is_uploaded_file($tmp_name)){

                                $name .= (is_null($name)) ? $this->generateUniqueName() . "." . $this->ext($regular_name): "";
                                $destination .= $name;
                                if (move_uploaded_file($tmp_name, $destination)) {
                                        chmod($destination, 0644);
                                        
                                        return array(
                                            'result' => 1,
                                            'name' => $name,
                                            'path' => $destination
                                        );

                                }
                                else {
                                    throw new Exception("Could not move '$name' to '$destination'");
                                }

                            }
                            else {
                                new FileException(102);
                            }

                        }
                        else {
                            return array(
                                'result' => 0
                            );
                        }
                        
                    }
                    else {
                        throw new FileException(101);
                    }

		}

                /*
                 * Method deletes file/s
                 *
                 * @param string $destination   root folder
                 * @param array $options
                 *                  'recursive' => false|true  recursive removing starting with destination
                 *                  'removeFolder' => false|true   including folders within root folder
                 */
                function removeFiles($destination = "", $options = null){

                    $destination = $this->checkSlashInPath($destination);

                    if ((strlen($destination) > 0) && (is_dir($destination))){

                        $content = $this->scanFolder($destination);
                        debug("CONTENT:" . $destination);
                        debug($content);//$folders_in_folder =

                        //deleting files
                        foreach ($content['files'] as $file){
                            if (!(unlink($destination . $file))){
                                throw FileException(108);
                            }
                        }

                        if (is_array($options) && $options['recursive']){


                                foreach ($content['folders'] as $folder) {
                                    $this->removeFiles($destination . $folder, array('recursive' => true, 'removeFolder' => true));

                                    //If there is removeFolder flag, folder is deleted also
                                    /*if (isset($options['removeFolder']) && $options['removeFolder']){
                                        rmdir($destination.$folder);
                                    }
                                    else {
                                        throw new FileException($destination, 101);
                                    }*/

                                }
                                

                        }

                        if (isset($options['removeFolder']) && $options['removeFolder']){
                            rmdir($destination);
                        }
                        else {
                            throw new FileException($destination, 101);
                        }

                    }
                    else {
                        throw new FileException($destination, 101);
                    }
                }

                /*
                 * Method deletes file/s
                 *
                 * @param string $destination   root folder which will be scanning for files
                 *
                 * @return array array with array of name of folders and array of name of files
                 */
                function scanFolder($destination = null){
                    $destination = $this->checkSlashInPath($destination);
                    $folders = array();
                    $files = array();
                    $temp_content = scandir($destination);

                    foreach ($temp_content as $c){
                        if ((strcmp($c,".") > 0) && (strcmp($c, "..") > 0)){
//debug($destination . $c);
                            //test if item is folder or file
                            if (is_dir($destination . $c)){
                                $folders[] = $c;
                            }
                            else {
                                $files[] = $c;
                            }
                            
                        }
                    }
                    
                    return array(
                        'folders' => $folders,
                        'files' => $files
                    );
                }

                /*
                 * Method generates random string with length 32 chars
                 *
                 * @return string unique name of file
                 */
                function generateUniqueName(){

                    return md5(time() . rand(1, 99999999));

                }

		// -- return the extension of a file
		function ext($file) {
			$ext = trim(substr($file,strrpos($file,".")+1,strlen($file)));
			return $ext;
		}

                function upload_error ($errorobj) {
                    $error = false;
                    switch ($errorobj) {
                       case UPLOAD_ERR_OK: break;
                       case UPLOAD_ERR_INI_SIZE: $error = 109; break;
                       case UPLOAD_ERR_FORM_SIZE: $error = 107; break;
                       case UPLOAD_ERR_PARTIAL: $error = 106; break;
                       case UPLOAD_ERR_NO_FILE: $error = 105; break;
                       case UPLOAD_ERR_NO_TMP_DIR: $error = 103; break;
                       case UPLOAD_ERR_CANT_WRITE: $error = 104; break;
                       //default: $error = 100;
                    }
                    if ($errorobj != UPLOAD_ERR_OK){
                        throw new FileException($error);
                    }
                    else {
                        return $error;
                    }
                    
		}

                /*
                 * Checks if path ends with slash. If there is not slash, method concatenates slash to string
                 *
                 * @param string $destination   checked destination path
                 * @return string path
                 */
                function checkSlashInPath($destination){
                    if (substr($destination,-1) != '/') {
                        return $destination .= '/';
                    }
                    return $destination;
                }

                function makeDir($destination){
                    $destination = $this->checkSlashInPath($destination);

                    if (!is_dir($destination)){
                        mkdir($destination, 0777, true);
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                
                function move_file($oldname, $newname){
                	rename($oldname, $newname);
                }
	}
	class FileException extends Exception {

    	var $exceptions = array(
                '101' => "Destination folder does not exist",
                '102' => "Uploaded file does not exist",
                '103' => "Missing a temporary folder.",
                '104' => "Failed to write file to disk",
                '105' => "No file was uploaded.",
                '106' => "The uploaded file was only partially uploaded.",
                '107' => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
                '108' => "File cannot be deleted",
                '109' => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
                '199' => "Unknown File Error",
            );
            
            public function __construct($code = 0, $message = "") {
                
                // make sure everything is assigned properly
                parent::__construct($this->exceptions[$code] . " : " . $message, $code);
            }
        }