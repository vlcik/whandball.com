<?php

class Category extends AppModel {
	
	public $actsAs = array('Tree');
	
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A username is required'
			)
		),
		'parent_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A parent category is required'
			)
		),
		'status_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A Status is required'
			)
		)
	);
}

?>