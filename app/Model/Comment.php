<?php

App::uses('AuthComponent', 'Controller/Component');
class Comment extends AppModel {
	public $validate = array(
			'name' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'A name is required'
					),
					'nameRestriction' => array(
							'rule' => array('check_name'),
							'message' => 'Pokúsili ste sa komentovať pod vyhradeným menom. Použite iné, prosím.'
					)
			),
			'content' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'A Content is required'
					)
			)
	);

	public $belongsTo = array('Article');
	
	function check_name(){
		$names = array('WHandball', 'Wbk');
		
		if (in_array($this->data['Comment']['name'], $names)){
			return false;
		}
		
		return true;
	}
}

?>