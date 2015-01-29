<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $helpers = array('Text');
	public $components = array(
			'Acl',
			'Session',
			'Auth' => array(
					'authorize' => array(
							'Actions' => array('actionPath' => 'controllers')
					)
			),
	);

	function beforeFilter() {
			
		$this->Auth->userModel = 'Group';
		$this->Auth->authenticate = array(
				'Form' => array(
						'scope' => array(
								'User.status_id' => 1
						)
				)
		);
		$this->Auth->loginError = "Wrong credentials. Please provide a valid username and password.";
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'editor' => false);
		$this->Auth->unauthorizedRedirect = array('controller' => 'pages', 'action' => 'page_401', 'admin' => false, 'editor' => false);

		$this->init_menu_element();

		$this->loggedUser = $this->Auth->user();

		if (($this->loggedUser['group_id'] == ADMIN) && preg_match("/^admin/", $this->action)) {
			$this->layout = 'admin_default';
		}
		else if (($this->loggedUser['group_id'] == EDITOR) && preg_match("/^editor/", $this->action)) {
			$this->layout = 'editor_default';
		}
		else {
			$this->layout = 'default';
		}
		
		if (isset($this->request->params['page'])) {
			$this->request->params['named']['page'] = $this->request->params['page'];
		}
		
		$this->getLastComments();
		$this->getMostCommented();
		$this->getLastCommentedArticles();
	}

	function _setErrorLayout() {
		if ($this->name == 'CakeError') {
			$this->layout = 'error';
		}
	}

	function beforeRender () {

		$this->_setErrorLayout();
	}

	function getMostCommented(){
		if (($mostCommented = Cache::read('most_commented', 'short')) === false) {
	
			$this->loadModel('Comment');
			$mostCommented = $this->Comment->find('all', array(
	
					'conditions' => array(
							'Comment.status_id' => ITEM_ACTIVE
					),
					'fields' => array(
							'count(Comment.id) as cnt', 'Comment.article_id',
							'Article.id', 'Article.title', 'Article.seo_title'
					),
					'order' => array(
							'cnt' => 'DESC'
					),
					'contain' => array('Article'),
					'group' => 'Comment.article_id',
					'limit' => 5
			));
	
			Cache::write('most_commented', $mostCommented, 'short');
	
		}
		$this->set('mostCommented', $mostCommented);
	}
	
	function getLastComments(){
		if (($lastComments = Cache::read('last_comments', 'short')) === false) {
		
			$this->loadModel('Comment');
			$lastComments = $this->Comment->find('all', array(
		
					'conditions' => array(
							'Comment.status_id' => ITEM_ACTIVE
					),
					'fields' => array(
							'Comment.id',
							'Comment.name',
							'Comment.content',
							'Comment.created',
							'Article.id',
							'Article.seo_title',
							'Article.title'
					),
					'order' => array(
							'Comment.id' => 'DESC'
					),
					'contain' => array('Article'),
					'limit' => 5
			));
				
			Cache::write('last_comments', $lastComments, 'short');
		
		}
		
		$this->set('lastComments', $lastComments);
	}
	
	function getLastCommentedArticles(){
		if (($lastCommentedArticles = Cache::read('last_commented_articles', 'short')) === false) {
	
			$this->loadModel('Comment');
			$lastCommentedArticles = $this->Comment->find('all', array(
					'conditions' => array(
							'Comment.status_id' => ITEM_ACTIVE
					),
					'fields' => array(
							'DISTINCT Article.id',
							'Article.seo_title',
							'Article.title',
							'Article.last_comment_time'
					),
					'order' => array(
							'Article.last_comment_time' => 'DESC'
					),
					'contain' => array('Article'),
					'limit' => 5
			));
			Cache::write('last_commented_articles', $lastCommentedArticles, 'short');
	
		}
	
		$this->set('lastCommentedArticles', $lastCommentedArticles);
	}
	
	function init_menu_element(){
		if (($root_categories = Cache::read('menu_items', 'short')) === false) {

			$this->loadModel('Category');
			$root_categories = $this->Category->find('all', array(

					'conditions' => array(
							'Category.parent_id' => ROOT_CATEGORY,
							'Category.status_id' => ITEM_ACTIVE
					),
					'fields' => array(
							'Category.id',
							'Category.name'
					),
					'order' => array(
							'Category.id' => 'DESC'
					),
					'limit' => 10
			));
			
			Cache::write('menu_items', $root_categories, 'short');
				
		}

		//prelozi nadpis a popis
		//$new_ads = $this->translate_title_description($new_ads);
		$this->set('menu_items', $root_categories);
	}

}