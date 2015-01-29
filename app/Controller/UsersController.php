<?php
App::uses('AppController', 'Controller');
App::uses('Component', 'Controller');

class UsersController extends AppController {

	public $name = 'Users';

	public $helpers = array('Form', 'Html', 'Js', 'Time');

	public $components = array('Password');

function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('login', 'logout');
	}

	function login(){
		$is_form_send = !empty($this->request->data['User']);
		if ($this->Auth->login()) {
			$lUser = $this->Auth->user();
			
			if ($lUser['group_id'] == ADMIN){
				$this->layout = 'admin_default';
				//$this->redirect($this->Auth->redirect());
				$this->redirect(array('controller' => 'articles', 'action' => 'index', 'admin' => true));
			}
			else if ($lUser['group_id'] == EDITOR){
				$this->layout = 'editor_default';
				//return $this->redirect($this->Auth->redirect());
				$this->redirect(array('controller' => 'articles', 'action' => 'index', 'editor' => true));
			}
			else {
				throw new MissingLayoutException();
			}
				
		} else if ($is_form_send){
				$this->Session->setFlash(__('Nesprávne meno, alebo heslo. Skúste to znova.', true), 'error', array(), 'auth');
		}
	}

	function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function admin_index() {

		$this->paginate = array(
				'conditions' => array(
						array(
								'NOT' => array(
										'User.status_id' => USER_DELETED
								)
						)
				),
				'fields' => array(
						'User.id',
						'User.username',
						'User.name',
						'User.surname',
						'User.email',
						'User.group_id',
						'User.status_id',
						'User.created',
						'User.modified',
				),
				'order' => array(
						'User.created' => 'ASC'
				),
				'limit' => 10
		);

		$this->set('editors', $this->paginate('User'));
	}

	public function admin_add() {
		if (!(empty($this->request->data))){
			$password = $this->Password->generate(8);
			$this->User->set($this->request->data);
			$this->User->data['User']['password'] = $this->Auth->password($password);
				
			if ($this->User->validates()) {
				//ulozenie dat
				if ($this->User->save()) {
					$this->Session->setFlash(__('User was successfully saved.', true), 'success');
					$this->redirect('/admin/users');
				}
				else {
					$this->Session->setFlash(__('An error occured while saving user. Try again, please.', true), 'error');
				}
			}
			else {
				$this->Session->setFlash(__('An error occured while saving user. Try again, please.', true), 'error');
			}
		}

		$this->set('is_edit', false);
		$this->render('admin_add');
	}

	function admin_edit($id = null){

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->set($this->request->data);
			if ($this->User->validates()) {
				if ($this->User->save()) {
					
					$this->Session->setFlash(__('The user has been saved', 'success'));
					$this->redirect('/admin/users');
				}
				else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.', 'error'));
				}
			}
		}
		else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}

		$this->set('is_edit', true);
		$this->render('admin_add');

	}

	function admin_delete($id = null){

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('get')) {
			if ($this->User->saveField('status_id', USER_DELETED)) {
				$this->Session->setFlash(__('The user has been removed'), 'success');
				$this->redirect('/admin/users');
			}
			else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'error');
			}
		}

		$this->redirect('/admin/users');
	}

	function editor_profile(){
		$this->admin_profile();
	}

	function admin_profile(){
		$this->User->id = $this->loggedUser['id'];

		$user = $this->User->find('first', array(
				'conditions' => array(
						'User.id' => $this->User->id
				),
				'recursive' => 1
		));
		
		
		if (!(empty($this->request->data))){
			
			$this->User->set($this->request->data);

			if (strlen($this->request->data['User']['oldpass']) > 0){
				if (strcmp($this->Auth->password($this->request->data['User']['oldpass']), $user['User']['password']) == 0){
					if ((strcmp($this->Auth->password($this->request->data['User']['newpass']), $this->Auth->password($this->request->data['User']['newpass2'])) == 0)
							&& (strlen($this->request->data['User']['newpass']) > 0)){

						$this->User->data['User']['password'] = $this->request->data['User']['newpass'];
					}
					else {
						$this->User->invalidate('newpass2', __('Heslá sa nezhodujú.', true));
					}
				}
				else {
					$this->User->invalidate('oldpass', __('Zadali ste nesprávne Vaše terajšie heslo.', true));
				}
			}
			
			$this->User->data['User']['status_id'] = $user['User']['status_id'];
			if ($this->User->save()) {
				$user = $this->User->find('first', array(
						'conditions' => array(
								'User.id' => $this->User->id
						),
						'recursive' => 1
				));

				$this->Session->setFlash(__('Profile was successfully changed.', true), 'success');
			}
			else {
				$this->Session->setFlash(__('An error occurred while saving profile.', true), 'error');
			}
		}

		$this->request->data = $user;
		$this->render('profile');
	}

}
