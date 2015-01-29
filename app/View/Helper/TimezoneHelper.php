<?php

class TimezoneHelper extends AppHelper {

	var $helpers = array('Session');

	/*function get_user_time($time = null,$format = null){
		//App::import('Helper','Session');
	//$this->Session = new SessionHelper();
	if(!is_numeric($time)){
	$time = strtotime($time);
	}

	$time += $this->Session->read('user.convert_seconds');

	if(empty($format)){
	$format = 'Y-m-d H:i:s';
	}

	return date($format,$time);
	}*/
	function get_user_time_tz($format = null, $time = null, $timezone = 'UTC'){
		return $this->get_user_time($time, $format, $timezone);
	}

	function get_user_time($time = null, $format = null, $timezone = 'UTC'){

		if(!is_numeric($time)){
			$time = strtotime($time);
		}
		if(empty($timezone) || $timezone == null){
			if ($this->Session->check('user.php_timezone')){
				$timezone = $this->Session->read('user.php_timezone');
			}
			else {
				$timezone = date_default_timezone_get();
			}
		}
		
		$utz 	= new DateTime(date('Y-m-d',$time), new DateTimeZone($timezone));
		$utzo 	= $utz->getOffset();
		$time 	+= $utzo;

		if(empty($format)){
			$format = 'Y-m-d H:i:s';
		}

		return date($format,$time);
	}

	function reverse_user_time($time = null,$format = null,$timezone = null){
		if(!is_numeric($time)){
			$time = strtotime($time);
		}
		if(empty($timezone) || $timezone == null){
			$timezone = $this->Session->read('user.php_timezone');
		}
		$utz = new DateTime(date('Y-m-d',$time), new DateTimeZone($timezone));
		$utzo = $utz->getOffset();
		$time -= $utzo;

		if(empty($format)){
			$format = 'Y-m-d H:i:s';
		}

		return date($format,$time);
	}

	/*function reverse_user_time($time = null,$format = null){
		if(!is_numeric($time)){
	$time = strtotime($time);
	}

	$time -= $this->Session->read('user.convert_seconds');

	if(empty($format)){
	$format = 'Y-m-d H:i:s';
	}

	return date($format,$time);
	} */

}
?>