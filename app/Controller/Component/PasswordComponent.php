<?php

    /*
     * PasswordComponent
     * 
     * @author Juraj Vlk juraj.vlk@gmail.com
     */

    class PasswordComponent extends Component {


        var $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'z', 'q', 'y', 'w',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Z', 'Q', 'Y', 'W',
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
        );

	    public function initialize(Controller $controller) {
	        
	    }

        /**
         * generate random string combined letters and numbers
         *
         * @param int $length length of string
         * @return string random string
         */

        function generate($length = 8) {
            $password = "";
            for ($i = 0; $i < $length; $i++){
                $password .= $this->chars[rand(0, (count($this->chars) - 1))];
            }

            return $password;
        }

    }
	
?>