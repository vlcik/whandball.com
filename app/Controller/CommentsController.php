<?php
App::uses('AppController', 'Controller');

class CommentsController extends AppController {

	public $name = 'Comments';

	public $uses = array('Comment', 'Article', 'BannedIp');

	public $components = array('RequestHandler', 'Captcha');

	public $helpers = array('Form', 'Html', 'Js', 'Captcha');

	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('add');
	}

	public function admin_index($article_id) {

		$this->paginate = array(
				'conditions' => array(
						'NOT' => array(
								'Comment.status_id' => ITEM_DELETED
						),
						'Comment.article_id' => $article_id
				),
				'fields' => array(
						'Comment.id',
						'Comment.name',
						'Comment.status_id',
						'Comment.ip',
						'Comment.created',
						'Comment.modified',
				),
				'order' => array(
						'Comment.modified' => 'ASC'
				),
				'limit' => 10
		);
		$comments = $this->paginate('Comment');
		$this->set('comments', $comments);

		/** retrieving appropriate article **/
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'NOT' => array(
								'Article.status_id' => ITEM_DELETED
						),
						'Article.id' => $article_id
				),
				'fields' => array(
						'Article.id',
						'Article.title'
				),
				'recursive' => -1
		));

		if (empty($article)){
			throw new NotFoundException('Could not find that article');
		}

		$this->set('article', $article);

		$this->set('inactive_comments', $this->Comment->find('count', array(
				'conditions' => array(
						'NOT' => array(
								'Comment.status_id' => ITEM_DELETED
						),
						'Comment.status_id' => ITEM_INACTIVE,
						'Article.id' => $article_id
				),
				'contain' => array('Article')
		)));

		$ips = array();
		foreach($comments as $comment){
			$ip = $comment['Comment']['ip'];
			if (!in_array($ip, $ips)){
				$ips[] = $ip;
			}
		}

		$bis = $this->BannedIp->find('all', array(
				'conditions' => array(
						'BannedIp.ip' => $ips
				),
				'fields' => array(
						'BannedIp.ip'
				)
		));
		$banned_ips = array();
		foreach($bis as $ban_ip){
			$banned_ips[] = $ban_ip['BannedIp']['ip'];
		}
		$this->set('banned_ips', $banned_ips);

	}

	public function admin_change_comment_status($comment_id) {
		$this->Comment->id = $comment_id;
		$comment = $this->Comment->find('first', array(
				'conditions' => array(
						'Comment.id' => $comment_id
				)
		));

		if (empty($comment)){
			throw new NotFoundException();
		}

		if ($this->request->is('get')) {
			$status = ($comment['Comment']['status_id'] == ITEM_ACTIVE) ? ITEM_INACTIVE : ITEM_ACTIVE ;
			if ($this->Comment->saveField('status_id', $status)) {
				$this->Session->setFlash(__('The status of comment with ID: ' . $comment_id . ' has been changed'), 'success');
				$this->redirect('/admin/comments/index/' . $comment['Comment']['article_id']);
			}
			else {
				$this->Session->setFlash(__('The status of comment could not be changed. Please, try again.'), 'error');
			}
		}
		$this->redirect('/admin/comments/index/' . $comment['Comment']['article_id']);
	}

	public function admin_ban_ip_address($article_id, $ip) {
				
		if ($this->request->is('get')) {
			$this->BannedIp->data['BannedIp']['ip'] = $ip;
			if ($this->BannedIp->save()) {
				$this->Session->setFlash(__('The IP address: ' . $ip . ' has been banned'), 'success');
				$this->redirect('/admin/comments/index/' . $article_id);
			}
			else {
				$this->Session->setFlash(__('The IP address: ' . $ip . ' could not be banned. Please, try again.'), 'error');
			}
		}
		$this->redirect('/admin/comments/index/' . $this->params->query['article_id']);
	}
	
	public function admin_view($id) {
	
		$comment = $this->Comment->find('first', array(
				'conditions' => array(
						'Comment.id' => $id
				),
				'fields' => array(
						'Comment.id',
						'Comment.content'
				),
				'recursive' => -1
		));
	
		if (empty($comment)){
			throw new NotFoundException('Could not find that comment');
		}
	
		$this->set('comment', $comment);
	}
	
	public function add(){
				
		if (!(empty($this->request->data))){
			$this->Comment->set($this->request->data);
			$this->Comment->data['Comment']['status_id'] = ITEM_ACTIVE;
			$this->Comment->data['Comment']['ip'] = $this->request->clientIp();
			$this->Comment->data['Comment']['article_id'] = $this->request->data['Comment']['article_id'];
			
			$this->loadModel('Article');
			$article = $this->Article->read('seo_title', $this->request->data['Comment']['article_id']);
			
			if($this->Captcha->validateCaptcha()){
				if (!$this->isIpAddressBanned($this->Comment->data['Comment']['ip'])){
					if ($this->Comment->validates()) {
						if ($this->Comment->save()) {
							
							Cache::delete('most_commented', 'short');
							Cache::delete('last_comments', 'short');
							Cache::delete('last_commented_articles', 'short');
							
							$this->Article->create();
							$this->Article->id = $this->request->data['Comment']['article_id'];
							$this->Article->saveField('last_comment_time', date('Y-m-d H:i:s', time()));
							
							$this->Session->setFlash(__('Your comment was successfully added.', true), 'success');
							$this->redirect(
									array(
											'controller' => 'articles',
											'action' => 'show',
											'id' => $this->request->data['Comment']['article_id'],
											'seo_title' => $article['Article']['seo_title'],
									)
							);
						}
						else {
							$this->Session->setFlash(__('Error occured while saving Comment. Please try again later.', true), 'error');
						}
					}
					else {
						$this->Session->setFlash(__('Some input fields in form were not filled correctly. Please, fill it and submit again.', true), 'error');
					}
				}
				else {
					$this->Session->setFlash(__('Your IP address is banned. It means that you are not allowed to add comments. Sorry.', true), 'error');
				}
			}
			else {
				$this->request->data = $this->Comment->data;
				$this->Session->setFlash(__('You typed incorrect control text - captcha, please try to add comment again', true), 'error');
			}
			
		}
		$this->redirect(
				array(
						'controller' => 'articles',
						'action' => 'show',
						'id' => $this->request->data['Comment']['article_id'],
						'seo_title' => $article['Article']['seo_title'],
				)
		);
	}
	
	private function isIpAddressBanned($ip_address){
		$ipAddress = $this->BannedIp->find('first', array(
				'conditions' => array(
						'BannedIp.ip' => $ip_address
				),
				'recursive' => -1
		));
	
		return !empty($ipAddress);
	}
}
