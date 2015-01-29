<?php

class ArticleSource extends AppModel {
	
	public $validate = array(
		'description' => array(
			'required' => false,
			'rule'    => array('inList', array(ARTICLE_BLOCK, ARTICLE_NON_STATIC, ARTICLE_STATIC)),
			'message' => 'An article type is required'
		),
	);
		
	public $belongsTo = array('Article');
    
}

?>