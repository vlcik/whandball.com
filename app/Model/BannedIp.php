<?php

class BannedIp extends AppModel {
	public $validate = array(
			'ip' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'An IP address is required'
					),
					'pattern'=>array(
							'rule'      => 'ip',
							'message'   => 'Only specific pattern of IP address is allowed',
					),
					'unique'=>array(
							'rule'      => 'isUnique',
							'message'   => 'Only Unique IP address could be saved',
					),
			)
	);
}

?>