<?php

App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {

	public $belongsTo = array('Group');
	public $actsAs = array('Acl' => array('type' => 'requester', 'enabled'
			=> false));

	public function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	public $validate = array(
			'username' => array(
					'username_not_empty' => array(
							'required' => true,
							'rule' => 'notEmpty',
							'message' => 'A username is required'
					)/*,
					'username_unique' => array(
							'required' => true,
							'rule' => 'unique',
							'message' => 'A username need to be unique'
					),*/

			),
			'email' => array(
					'email_email' => array(
							'required' => true,
							'rule' => 'email',
							'message' => 'An email is not in valid form'
					),
					'email_not_empty' => array(
							'required' => true,
							'rule' => 'notEmpty',
							'message' => 'An email is required'
					)
			),
			'group_id' => array(
					'rule'    => array('inList', array(ADMIN, EDITOR)),
					'message' => 'Please enter a valid role'
			),
			'status_id' => array(
					'in' => array(
							'required' => true,
							'rule'    => array('inList', array(ITEM_ACTIVE, ITEM_INACTIVE, ITEM_DELETED)),
							'message' => 'A Status is required'
					),
			),
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

	public function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['group_id'])) {
			$groupId = $this->data['User']['group_id'];
		} else {
			$groupId = $this->field('group_id');
		}
		if (!$groupId) {
			return null;
		} else {
			return array('Group' => array('id' => $groupId));
		}
	}
}

?>