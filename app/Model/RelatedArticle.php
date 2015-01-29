<?php

App::uses('AppModel', 'Model');

class RelatedArticle extends AppModel {
	
	public $validate = array(
		
		'article_id' => array(
			'required' => false,
			'rule'    => array('notEmpty'),
			'message' => 'An article type is required'
		),
	);
		
	public $belongsTo = array('Article');
    
}

?>