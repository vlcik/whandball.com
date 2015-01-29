<?php

    /*
     * ImageComponent
     * 
     * @author Juraj Vlk juraj.vlk@gmail.com
     */
App::uses('Component', 'Controller');

    class ImageHandlerComponent extends Component {

        var $destination = null;
        var $canvas = null;
        var $width = 0;
        var $height = 0;
        var $type = null;
        
	    private $controller;
	   	private $count = 0;
	   	private $_allowed;
	   	private $errors;
	    	
	    public function initialize(Controller $controller) {
			$this->controller = $controller;  
	    }
        
        public function init($destination = null){
            
            if (!is_null($destination) && file_exists($destination) && !is_dir($destination)){

                $this->destination = $destination;

                $info = getimagesize($destination);

                $this->width = $info[0];
                $this->height = $info[1];
                $this->type = $info[2];

                //init canvas
                $this->loadCanvas();
                
            }
            else {
                throw new ImageComponentException($destination, 201);
            }
        }

        function loadCanvas(){

            if( $this->type == IMAGETYPE_JPEG ) {
                $this->canvas = imagecreatefromjpeg($this->destination);
            } elseif( $this->type == IMAGETYPE_GIF ) {
                $this->canvas = imagecreatefromgif($this->destination);
            } elseif( $this->type == IMAGETYPE_PNG ) {
                $this->canvas = imagecreatefrompng($this->destination);
            }

        }


        function resize($width,$height) {

            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($new_image, $this->canvas, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

            $this->canvas = $new_image;
        }

        function scale($ratio = 100) {

            $width = $this->width * ($ratio/100);
            $height = $this->height * ($ratio/100);
            $this->resize($width,$height);

        }

        function resizeByWidth($width){
            $ratio = $width / $this->width;
            $height = $this->height * $ratio;
            $this->resize($width, $height);
        }

        function resizeByHeight($height){
            $ratio = $height / $this->height;
            $width = $this->width * $ratio;
            $this->resize($width, $height);
        }

        function save($filename, $image_type = null, $compression = 75, $permissions = null) {

            $type = null;
            if ($image_type != null){
                $type = $image_type;
            }
            else {
                $type = $this->type;
            }

            //saving
            if( $type == IMAGETYPE_JPEG ) {
                return imagejpeg($this->canvas, $filename, $compression);
            } elseif( $type == IMAGETYPE_GIF ) {
                return imagegif($this->canvas, $filename);
            } elseif( $type == IMAGETYPE_PNG ) {
                return imagepng($this->canvas, $filename);
            }

            if( $permissions != null) 
                chmod($filename, $permissions);
        }

        function flush($image_type = null) {

            $type = null;
            if ($image_type != null){
                $type = $image_type;
            }
            else {
                $type = $this->type;
            }

            if( $type == IMAGETYPE_JPEG ) {
                imagejpeg($this->canvas);
            } elseif( $type == IMAGETYPE_GIF ) {
                imagegif($this->canvas);
            } elseif( $type == IMAGETYPE_PNG ) {
                imagepng($this->canvas);
            }

            $this->canvas = null;
   }



    }

    class ImageComponentException extends Exception {

            var $exceptions = array(
                '201' => "Destination file does not exist",
                
            );

            public function __construct($message = "", $code = 0) {

                // make sure everything is assigned properly
                parent::__construct($this->exceptions[$code] . " : " . $message, $code);
            }
        }
	
?>