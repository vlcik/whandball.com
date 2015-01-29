<?php

class ArticleCategory extends AppModel {
	public $actsAs = array('Containable');
	
	public $validate = array(
			'article_id' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'An article ID is required'
					),
					'numberic' => array(
							'rule' => array('numeric'),
							'message' => 'An article ID need to be in numeric format'
					)
			),
			'category_id' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'A category is required'
					),
					'numberic' => array(
							'rule' => array('numeric'),
							'message' => 'An category ID need to be in numeric format'
					)
			)
	);
	
	public $belongsTo = array('Category', 'Article');
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()){
		$joins = array(
				array('table'=>'articles',
						'alias' => 'Article',
						'type'=>'LEFT',
						'conditions'=> array(
								'ArticleCategory.article_id = Article.id'
						)
				),
				array('table'=>'users',
						'alias' => 'User',
						'type'=>'LEFT',
						'conditions'=> array(
								'Article.user_id = User.id'
						)),
				array('table'=>'images',
						'alias' => 'Image',
						'type'=>'LEFT',
						'conditions'=> array(
								'Image.article_id = Article.id',
								'Image.image_type_id' => MAIN_ARTICLE_IMAGE
						)
				)
		);
		
		$count = $this->find('count', array(
				'fields' => 'DISTINCT Article.id',
				'joins' => $joins,
				'conditions' => $conditions
		));
		return $count;
	}
	
}

?>