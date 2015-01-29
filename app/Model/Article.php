<?php

class Article extends AppModel {
	
	public $validate = array(
		'perex' => array(
				'required' => array(
						'rule' => array('notEmpty'),
						'message' => 'A perex is required'
				)
		),
			
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A title is required'
			)
		),
		'article_type_id' => array(
			'required' => false,
			'rule'    => array('inList', array(ARTICLE_BLOCK, ARTICLE_NON_STATIC, ARTICLE_STATIC)),
			'message' => 'An article type is required'
		),
	);
		
	public $hasMany = array('Comment', 'Image', 'RelatedArticle', 'ArticleSource');
	
	//public $hasAndBelongsToMany = array('ArticleCategory');
	public $belongsTo = array('User', 'ArticleCategory');
    
}

?>