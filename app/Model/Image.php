<?php

class Image extends AppModel {
	
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A image name is required'
			)
		)
	);
	
	public $belongsTo = 'Article';
    
}

?>