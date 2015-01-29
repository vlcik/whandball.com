<?php
App::uses('AppController', 'Controller');

class ArticlesController extends AppController {

	public $name = 'Articles';
	
	public $components = array('CategoryTree', 'File', 'ImageHandler', 'RequestHandler', 'Seo', 'Captcha');

	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Tinymce', 'Captcha', 'Category', 'Timezone');

	function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('home', 'get_articles_by_category', 'show', 'captcha', 'get_related_articles', 'user_articles_list', 'search', 'advanced_search');
	}

	public function editor_index() {

		$this->paginate = array(
				'conditions' => array(
						'NOT' => array(
								'Article.status_id' => ARTICLE_DELETED
						),
						'Article.user_id' => $this->loggedUser['id']
				),
				'fields' => array(
						'Article.id',
						'Article.title',
						'Article.user_id',
						'User.username',
						'Article.article_type_id',
						'Article.status_id',
						'Article.created',
						'Article.modified',
				),
				'order' => array(
						'Article.modified' => 'DESC'
				),
				'limit' => 10
		);

		$this->set('articles', $this->paginate('Article'));
		$this->set('is_admin_mode', false);
		$this->render('index');
	}

	public function admin_index() {

		$this->paginate = array(
				'conditions' => array(
						'NOT' => array(
								'Article.status_id' => ARTICLE_DELETED
						)
				),
				'fields' => array(
						'Article.id',
						'Article.title',
						'Article.user_id',
						'User.username',
						'Article.article_type_id',
						'Article.status_id',
						'Article.created',
						'Article.modified',
				),
				'order' => array(
						'Article.modified' => 'DESC'
				),
				'limit' => 20,
				'contain' => array('User')
		);

		$this->set('articles', $this->paginate('Article'));
		$this->set('is_admin_mode', true);
		$this->render('index');
	}

	public function editor_view($id) {
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.user_id' => $this->loggedUser['id'],
						'Article.id' => $id
				),
				'recursive' => -1
		));

		if (empty($article)){
			throw new UnauthorizedException();
		}

		$this->admin_view($id);
	}

	public function admin_view($id) {

		$this->loadModel('Article');
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.id' => $id
				),
				'contain' => array('Image')
		));

		if (empty($article)){
			throw new NotFoundException('Could not find that article');
		}

		$this->loadModel('ArticleCategory');
		$categories = $this->ArticleCategory->find('all', array(
				'conditions' => array(
						'ArticleCategory.article_id' => $id
				)
		));

		$category_paths = array();
		foreach ($categories as $category){
			$category_paths[] = $this->CategoryTree->get_category_path(array(), $category['ArticleCategory']['category_id']);
		}

		$lUser = $this->Auth->user();
		$this->set('paths', $category_paths);
		$this->set('is_admin_mode',  $lUser['group_id'] == ADMIN);
		$this->set('article', $article);
		$this->render('view');
	}

	public function editor_add() {
		$this->admin_add();
	}

	public function admin_add() {

		$this->loadModel('Article');
		$this->loadModel('ArticleCategory');
		$this->loadModel('Image');
		
		if (!(empty($this->request->data))){

			$redirectUrl = "/admin/articles";
			if ($this->loggedUser['group_id'] == EDITOR){
				$redirectUrl = "/editor/articles";
			}

			$this->Article->set($this->request->data);
			$this->Article->data['Article']['user_id'] = $this->loggedUser['id'];
			$this->Article->data['Article']['hot_update_time'] = $this->Article->data['Article']['publish_time'];
			if ($this->Article->validates()) {
				$dataSource = $this->Article->getDataSource();
				$dataSource->begin();

				$this->Article->data['Article']['seo_title'] = $this->Seo->format($this->Article->data['Article']['title']);
				if ($this->Article->save()) {

					{
						$categories_ids = explode(',', $this->request->data['Article']['category']);
						$data = array();
						foreach ($categories_ids as $category_id){
							$category = array();
							$category['ArticleCategory']['category_id'] = $category_id;
							$category['ArticleCategory']['article_id'] = $this->Article->id;
							$data[] = $category;
						}
						$this->ArticleCategory->saveMany($data);
					}
					{
						$this->loadModel('RelatedArticle');
						
						if (strcmp($this->request->data['Article']['related_articles'], "") != 0){
							$related_article_ids = explode(',', $this->request->data['Article']['related_articles']);
							$data = array();
							if (count($related_article_ids) > 0){
								foreach ($related_article_ids as $r_article_id){
									$ra = array();
									$ra['RelatedArticle']['related_article_id'] = $r_article_id;
									$ra['RelatedArticle']['article_id'] = $this->Article->id;
									$data[] = $ra;
								}
								$this->RelatedArticle->saveMany($data);
							}
						}
					}
					try {
						$trans_id = $this->request->data['Article']['trans_id'];
						$tmpUploadFolderPath = TEMP_UPLOAD_FOLDER_PATH . DIRECTORY_SEPARATOR . $trans_id;
						if (is_dir($tmpUploadFolderPath)){

							$items = $this->File->scanFolder($tmpUploadFolderPath);
							if (count($items['files']) > 0){

								$data = array();
								foreach ($items['files'] as $key => $item){
									$tmpFileName = TEMP_UPLOAD_FOLDER_PATH . DIRECTORY_SEPARATOR . $trans_id . DIRECTORY_SEPARATOR . $item;

									if (is_file($tmpFileName)){
										$fileInfo = pathinfo($tmpFileName);
										$newFileName = md5($item . $key) . DOT .  $fileInfo['extension'];
										$image = array();
										$image['Image']['name'] = $newFileName;
										$image['Image']['article_id'] = $this->Article->id;
										$data[] = $image;

										//Saving images in various sizes
										$path_mini = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'mini/';
										$path_small = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'small/';
										$path_large = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'large/';
										$path_original = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'original/';
										$path_box = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'box/';

										$this->File->makeDir($path_mini);
										$this->File->makeDir($path_small);
										$this->File->makeDir($path_large);
										$this->File->makeDir($path_original);
										$this->File->makeDir($path_box);
											
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_small, SMALL_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_box, BOX_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_large, LARGE_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_mini, MINI_IMAGE_WIDTH);
										rename($tmpFileName, $path_original . $newFileName);
									}

								}
								$this->Image->saveMany($data);

								$this->File->removeFiles($tmpUploadFolderPath, array(
										'removeFolder' => true
								));
							}
						}
					}
					catch(FileException $ex) {
						debug($ex->getMessage());
						$dataSource->rollback();
					}

					$dataSource->commit();

					$this->Session->setFlash(__('Article was successfully saved.', true), 'success');
					$this->redirect($redirectUrl);
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Po?as ukladania pou??vate?a vznikla chyba a neulo?il sa. Pros?m sk?ste ho ulo?i? e?te raz.', true), 'error');
				}
			}
		}

		$this->set('fileComponent', $this->File);
		$this->set('trans_id', md5(time() . rand(1, 99999999)));
		$this->set('is_edit', false);
		$lUser = $this->Auth->user();
		$this->set('is_admin_mode',  $lUser['group_id'] == ADMIN);
		$this->render('add');
	}

	private function saveImageInSpecifiedSize($imageComponent, $newFileName, $tmpFileName, $path, $size){
		$imageComponent->init($tmpFileName);
		if (is_dir($path)){
			$imageComponent->resizeByWidth($size);

			if (!$imageComponent->save($path . $newFileName, null, 100)){
				throw new ImageComponentException('Resizing and saving failed');
			}
		}
	}

	public function editor_edit($id = null) {
		
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.user_id' => $this->loggedUser['id'],
						'Article.id' => $id
				),
				'recursive' => -1
		));

		if (empty($article)){
			throw new UnauthorizedException();
		}

		$this->admin_edit($id);
	}

	public function admin_edit($id = null) {

		if (!empty($this->request->data)) {
			$id = $this->request->data['Article']['id'];
		}

		$this->loadModel('Category');
		$this->loadModel('Article');
		$this->loadModel('Image');
		$this->loadModel('ArticleCategory');
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.id' => $id,
				),
				'contain' => array('Image')
		));

		if (empty($article)){
			throw new NotFoundException('Could not find that article');
		}

		if (!(empty($this->request->data))){

			$redirectUrl = "/admin/articles";
			if ($this->loggedUser['group_id'] == EDITOR){
				$redirectUrl = "/editor/articles";
			}

			$this->Article->set($this->request->data);

			if ($this->Article->data['Article']['hot_update'] != 1){
				unset($this->Article->data['Article']['hot_update_time']);
			}
			
			if ($this->Article->validates()) {

				if (!empty($this->Article->data['Article']['title'])){
					$this->Article->data['Article']['seo_title'] = $this->Seo->format($this->Article->data['Article']['title']);
				}

				$this->Article->query('SET autocommit = 0');
				$this->Article->begin();

				//ulozenie dat
				if ($this->Article->save()) {

					{
						$this->ArticleCategory->deleteAll(
								array(
										'ArticleCategory.article_id' => $id
								),
								false
						);

						$categories_ids = explode(',', $this->request->data['Article']['category']);
						$data = array();
						foreach ($categories_ids as $category_id){
							$category = array();
							$category['ArticleCategory']['category_id'] = $category_id;
							$category['ArticleCategory']['article_id'] = $this->Article->id;
							$data[] = $category;
						}
						$this->ArticleCategory->saveMany($data);
					}
					
					{
						$this->loadModel('RelatedArticle');
						$this->RelatedArticle->deleteAll(
								array(
										'RelatedArticle.article_id' => $id
								),
								false
						);
						
						if (strcmp($this->request->data['Article']['related_articles'], "") != 0){
							$related_article_ids = explode(',', $this->request->data['Article']['related_articles']);
							$data = array();
							if (count($related_article_ids) > 0){
								foreach ($related_article_ids as $r_article_id){
									$ra = array();
									$ra['RelatedArticle']['related_article_id'] = $r_article_id;
									$ra['RelatedArticle']['article_id'] = $this->Article->id;
									$data[] = $ra;
								}
								$this->RelatedArticle->saveMany($data);
							}
						}
					}

					try {
						$trans_id = $this->request->data['Article']['trans_id'];
						$tmpUploadFolderPath = TEMP_UPLOAD_FOLDER_PATH . DIRECTORY_SEPARATOR . $trans_id;
						if (is_dir($tmpUploadFolderPath)){


							$items = $this->File->scanFolder($tmpUploadFolderPath);
							if (count($items['files']) > 0){
									
								$data = array();
								foreach ($items['files'] as $key => $item){
									$tmpFileName = TEMP_UPLOAD_FOLDER_PATH . DIRECTORY_SEPARATOR . $trans_id . DIRECTORY_SEPARATOR . $item;

									if (is_file($tmpFileName)){
										$fileInfo = pathinfo($tmpFileName);
										$newFileName = md5($item . $key) . DOT .  $fileInfo['extension'];
										$image = array();
										$image['Image']['name'] = $newFileName;
										$image['Image']['article_id'] = $this->Article->id;
										$data[] = $image;
											
										//Saving images in various sizes
										$path_mini = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'mini/';
										$path_small = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'small/';
										$path_large = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'large/';
										$path_original = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'original/';
										$path_box = IMAGE_ROOT_FOLDER . DS. $this->Article->id . DS . 'box/';
											
										$this->File->makeDir($path_mini);
										$this->File->makeDir($path_small);
										$this->File->makeDir($path_large);
										$this->File->makeDir($path_original);
										$this->File->makeDir($path_box);

										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_small, SMALL_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_box, BOX_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_large, LARGE_IMAGE_WIDTH);
										$this->saveImageInSpecifiedSize($this->ImageHandler, $newFileName, $tmpFileName, $path_mini, MINI_IMAGE_WIDTH);
										rename($tmpFileName, $path_original . $newFileName);
									}

								}
								$this->Image->saveMany($data);
									
								$this->File->removeFiles($tmpUploadFolderPath, array(
										'removeFolder' => true
								));
							}
						}
					}
					catch(FileException $ex) {
						debug($ex->getMessage());
						$dataSource->rollback();
					}
				}
				$this->Article->commit();
				$this->Session->setFlash(__('Article was successfully edited.', true), 'success');
				$this->redirect($redirectUrl);
			}
		}

		$this->ArticleCategory->belongsTo['Category']['fields'] = array(
				'Category.name'
		);
		
		$articleCategories = $this->ArticleCategory->find('all', array(
				'conditions' => array(
						'ArticleCategory.article_id' => $id,
				),
				'fields' => array(
						'ArticleCategory.category_id'
				),
				'contain' => array('Category')
		));
		$data = array();

		foreach ($articleCategories as $articleCategory){
			$ac = array();
			$ac['id'] = $articleCategory['ArticleCategory']['category_id'];
			$ac['name'] = $articleCategory['Category']['name'];
			$ac['path'] = $this->CategoryTree->get_string_path($this->CategoryTree->get_category_path(array(), $articleCategory['ArticleCategory']['category_id']));
			$data[] = $ac;
		}
		
		$this->loadModel('RelatedArticle');
		$related_articles = $this->RelatedArticle->find('all', array(
				'conditions' => array(
						'RelatedArticle.article_id' => $id,
				),
				'fields' => array(
						'Article.id', 'Article.title'
				),
				'contain' => array('Article' => array('foreignKey' => 'related_article_id'))
		));
		$related_articles_data = array();
		foreach ($related_articles as $re_article){
			$ra = array();
			$ra['id'] = $re_article['Article']['id'];
			$ra['title'] = $re_article['Article']['title'];
			$related_articles_data[] = $ra;
		}
		
		$this->set('trans_id', md5(time() . rand(1, 99999999)));
		$this->request->data = $article;
		$this->set('autocompleter_edit', json_encode($data));
		$this->set('autocompleter_edit_related_articles', json_encode($related_articles_data));
		$this->set('article', $article);
		$this->set('is_edit', true);
		$lUser = $this->Auth->user();
		$this->set('is_admin_mode',  $lUser['group_id'] == ADMIN);
		$this->render('add');
	}

	public function editor_delete($id) {
		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.user_id' => $this->loggedUser['id'],
						'Article.id' => $id
				),
				'recursive' => -1
		));

		if (empty($article)){
			throw new UnauthorizedException();
		}

		$this->admin_delete($id);
	}

	function admin_delete($id){

		$redirectUrl = "/admin/articles";
		if ($this->loggedUser['group_id'] == EDITOR){
			$redirectUrl = "/editor/articles";
		}

		$this->Article->id = $id;

		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}

		if ($this->request->is('get')) {
			$this->Article->data['Article']['status_id'] = ARTICLE_DELETED;
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been removed'), 'success');
				$this->redirect($redirectUrl);
			}
			else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'error');
			}
		}

		$this->redirect($redirectUrl);
	}

	function get_articles_by_category(){

		$category_id = $this->request->params['id'];
		$this->loadModel('Category');
		$this->loadModel('ArticleCategory');
		$is_home = false;
		if ($category_id == ROOT_CATEGORY){
			$is_home = true;
		}
		if (!$is_home){
			$category = $this->Category->find('first', array(
					'conditions' => array(
							'Category.id' => $category_id,
							'Category.status_id' => ITEM_ACTIVE
					)
			));
			$category_paths = $this->Category->getPath($category_id);
			$this->set('category', $category);
			$this->set('category_path', $category_paths);
		}

		$cats = $this->Category->children($category_id);
		$categories = array();
		$categories[] = $category_id;
		foreach ($cats as $c){
			$categories[] = $c['Category']['id'];
		}

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

		$now = date('Y-m-d H:i:s');
		$this->paginate = array(
				'ArticleCategory' => array(
						'fields' => array(
								'DISTINCT Article.id',
								'Article.title',
								'Article.perex',
								'Article.user_id',
								'Article.seo_title',
								'Article.media_type_id',
								'Article.publish_time',
								'Article.hot_update_time',
								'Article.hot_update',
								'User.id',
								'User.name',
								'User.surname',
								'User.username',
								'Image.id',
								'Image.name',
								'Image.description',
								'Article.article_type_id',
								'Article.status_id',
								'Article.created',
								'Article.modified',
						),
						'joins' => $joins,
						'conditions' => array(
								'Article.status_id' => ARTICLE_ACTIVE,
								'Article.article_type_id' => ARTICLE_NON_STATIC,
								'ArticleCategory.category_id' => $categories,
								'Article.publish_time < ' => $now
						),
						
						'order' => array(
								'Article.hot_update_time' => 'DESC',
						),
						'limit' => 10
						
				)
		);
		
		$articles = $this->paginate('ArticleCategory');

		$data = array();
		foreach ($articles as $article){
			$data[$article['Article']['id']] = $article;
			if ($article['Article']['hot_update'] == 1){
				$data[$article['Article']['id']]['Article']['sort_time'] = $article['Article']['hot_update_time'];
			}
			else {
				$data[$article['Article']['id']]['Article']['sort_time'] = $article['Article']['publish_time'];
			}
		}
		
		$this->loadModel('Comment');
		$comments_count = $this->Comment->find('all', array(
			'fields' => array('Comment.article_id', 'count(Comment.id) as count'),
			'conditions' => array(
				'Comment.article_id' => array_keys($data)
			),
			'group' => array('Comment.article_id')
		));
		
		foreach ($comments_count as $comment){
			$data[$comment['Comment']['article_id']]['comment_count'] = $comment[0]['count'];
		}
		
		$s = array();
		foreach ($data as $key => $row){
			$s[$key] = $row['Article']['sort_time'];
		}
		array_multisort($s, SORT_DESC, $data);

		$this->set('articles', $data);
		$this->set('is_home', $is_home);
		
		if (!$is_home){
			if (!empty($category)){
				$this->set('title_for_layout', " | " . $category['Category']['name']);
			}
		}
		else {
			$this->set('title_for_layout', "::: &#381;ensk&aacute; h&aacute;dzan&aacute; patri&#269;ne netradi&#269;ne");
		}
		
		$this->render('get_articles');

	}
	
	function home(){
		$this->get_articles_by_category(1);
	}

	function show($id){

		$this->loadModel('Article');
		if (!(empty($this->request->data))){
			$this->loadModel('Comment');
			$this->Comment->create();
			$this->Comment->set($this->request->data);
			$this->Comment->data['Comment']['status_id'] = ITEM_ACTIVE;
			$this->Comment->data['Comment']['ip'] = $this->request->clientIp();
			$this->Comment->data['Comment']['article_id'] = $this->request->data['Comment']['article_id'];
				
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
								
							$this->Session->setFlash(__('V&aacute;&#353; koment&aacute;r bol &uacute;spe&#353;ne pridan&yacute; do diskusie.', true), 'success');
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
							$this->Session->setFlash(__('Vyskytla sa chyba pri prid&aacute;van&iacute; koment&aacute;ru. Sk&uacute;ste to znova.', true), 'error');
						}
					}
					else {
						$this->Session->setFlash(__('Niektor&eacute; z povinn&yacute;ch pol&iacute; neboli vyplnen&eacute; alebo boli vyplnen&eacute; nespr&aacute;vne. Sk&uacute;ste to znova.', true), 'error');
					}
				}
				else {
					$this->Session->setFlash(__('Nem&ocirc;&#382;ete prid&aacute;va&#357; koment&aacute;re, preto&#382;e Va&#353;a IP adresa je zablokovan&aacute;.', true), 'error');
				}
			}
			else {
				$this->Session->setFlash(__('Nespr&aacute;vne op&iacute;san&yacute; k&oacute;d obr&aacute;zku (captcha), sk&uacute;ste to znova.', true), 'error');
			}
		}
		
		$this->loadModel('Category');
		$this->Article->hasMany['Comment']['conditions']['Comment.status_id'] = ITEM_ACTIVE;
		$this->Article->hasMany['Comment']['order']['Comment.created'] = 'ASC';

		$this->Article->hasMany['Image']['conditions']['Image.status_id'] = ITEM_ACTIVE;

		$article = $this->Article->find('first', array(
				'conditions' => array(
						'Article.id' => $id,
						'Article.status_id' => ITEM_ACTIVE
				),
				'contain' => array(
						'RelatedArticle' => array(
							'Article' => array(
								'foreignKey' => 'related_article_id',
								'fields' => array(
									'Article.id',
									'Article.title',
									'Article.seo_title'	
								)		
							)
						), 
						'Comment' => array(
							'order' => array(
								'Comment.created' => 'DESC'
							)	
						), 
						'Image', 
						'User'
				)
		));

		if (empty($article)){
			throw new NotFoundException('Could not find that article');
		}

		$this->loadModel('ArticleCategory');
		$categories = $this->ArticleCategory->find('all', array(
				'conditions' => array(
						'ArticleCategory.article_id' => $id
				),
		));

		$category_paths = array();
		foreach ($categories as $category){
			$category_paths[] = $this->Category->getPath($category['ArticleCategory']['category_id']);
		}

		$this->set('title_for_layout', "| " . $article['Article']['title']);
		$this->set('paths', $category_paths);
		$this->set('article', $article);

	}

	function captcha(){
		$this->Captcha->configCaptcha(array(
				'pathType' => 2
		)); 
		$this->Captcha->getCaptcha();
	}
	
	public function get_related_articles(){
		$this->layout = 'ajax';
		
		$articles = $this->Article->find('all', array(
				'conditions' => array(
						'Article.title LIKE' => "%" . $this->params->query['term'] . "%",
						'Article.status_id' => ITEM_ACTIVE,
				),
				'fields' => array(
						'Article.id',
						'Article.title'
				)
		));
		//debug($articles);
		$articles_output = array();
		foreach ($articles as $article){
			$art = array();
			$art['id'] = $article['Article']['id'];
			$art['title'] = $article['Article']['title'];
			$articles_output[] = $art;
		}
		$this->set('data', $articles_output);
		
		$this->render('/Elements/json');
	}
	
	public function user_articles_list($id){
		
		$this->loadModel('User');
		
		$join = array(
				array(
					'table'=>'images',
					'alias' => 'Image',
					'type'=>'LEFT',
					'conditions'=> array(
							'Image.article_id = Article.id',
							'Image.image_type_id' => MAIN_ARTICLE_IMAGE
					)
				),
				array('table'=>'users',
						'alias' => 'User',
						'type'=>'RIGHT',
						'conditions'=> array(
								'Article.user_id = User.id'
				)),
		);
		
		$now = date('Y-m-d H:i:s');
		$this->paginate = array(
				'joins' => $join,
				'conditions' => array(
						'Article.status_id' => ARTICLE_ACTIVE,
						'Article.article_type_id' => ARTICLE_NON_STATIC,
						'Article.publish_time < ' => $now,
						'Article.user_id' => $id
				),
				'fields' => array(
						'DISTINCT Article.id',
						'Article.title',
						'Article.perex',
						'Article.user_id',
						'Article.seo_title',
						'Article.media_type_id',
						'Article.publish_time',
						'Article.hot_update_time',
						'Article.hot_update',
						'User.id',
						'User.name',
						'User.surname',
						'User.username',
						'Image.id',
						'Image.name',
						'Image.description',
						'Article.article_type_id',
						'Article.status_id',
						'Article.created',
						'Article.modified',
				),
				'order' => array(
						'Article.hot_update_time' => 'DESC',
				),
				'limit' => 10
		);

		$articles = $this->paginate('Article');
	
		$data = array();
		foreach ($articles as $article){
			$data[$article['Article']['id']] = $article;
			if ($article['Article']['hot_update'] == 1){
				$data[$article['Article']['id']]['Article']['sort_time'] = $article['Article']['hot_update_time'];
			}
			else {
				$data[$article['Article']['id']]['Article']['sort_time'] = $article['Article']['publish_time'];
			}
		}
		
		$this->loadModel('Comment');
		$comments_count = $this->Comment->find('all', array(
			'fields' => array('Comment.article_id', 'count(Comment.id) as count'),
			'conditions' => array(
				'Comment.article_id' => array_keys($data)
			),
			'group' => array('Comment.article_id')
		));
		
		
		foreach ($comments_count as $comment){
			$data[$comment['Comment']['article_id']]['comment_count'] = $comment[0]['count'];
		}
		
		$s = array();
		foreach ($data as $key => $row){
			$s[$key] = $row['Article']['sort_time'];
		}
		array_multisort($s, SORT_DESC, $data);
		
		$user = $this->User->read(null, $id);
		$this->set('title_for_layout', "| &#268;l&aacute;nky autora" . $user['User']['name'] . " " . $user['User']['surname']);
		$this->set('user', $user);
		$this->set('articles', $data);
	}
	
	public function search(){
		
		$and = array();
		if (isset($this->request->query['q'])){
			$and['OR'] = array(
					'Article.title LIKE' => "%" . $this->request->query['q'] .	"%",
					'Article.perex LIKE' => "%" . $this->request->query['q'] .	"%",
					'Article.content LIKE' => "%" . $this->request->query['q'] .	"%",
			);
		}
		
		if (isset($this->request->query['advanced']) && ($this->request->query['advanced'] == 1)){
			$this->set('advanced', true);
			if (!empty($this->request->query['category_id'])){
				$this->loadModel('Category');
				$this->set('category', $this->Category->findById($this->request->query['category_id']));
				$cats = $this->Category->children($this->request->query['category_id']);
				$categories = array();
				$categories[] = $this->request->query['category_id'];
				foreach ($cats as $category){
					$categories[] = $category['Category']['id'];
				}
				$and['ArticleCategory.category_id'] = $categories;
			}
			
			if (!empty($this->request->query['from']) || !empty($this->request->query['to'])){
				
				if (!empty($this->request->query['from']) && !empty($this->request->query['to'])){
					$and['and'] = array(
							'Article.publish_time > ' => date("Y-m-d", strtotime($this->request->query['from'])) . " 00:00:00", 
							'Article.publish_time < ' => date("Y-m-d", strtotime($this->request->query['to'])) . " 23:59:59"
					);
				}
				else if (!empty($this->request->query['from'])){
					$and['Article.publish_time > '] = date("Y-m-d", strtotime($this->request->query['from'])) . " 00:00:00";
				}
				else if (!empty($this->request->query['to'])){
					$and['Article.publish_time < '] = date("Y-m-d", strtotime($this->request->query['to'])) . " 23:59:59";
				}
				
			}
		}
		
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
						'type'=>'RIGHT',
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
		
		$now = date('Y-m-d H:i:s');
		$this->paginate = array(
				'joins' => $joins,
				'conditions' => array(
						'Article.status_id' => ARTICLE_ACTIVE,
						'Article.article_type_id' => ARTICLE_NON_STATIC,
						'Article.publish_time < ' => $now,
						'AND' => $and
				),
				'fields' => array(
						'DISTINCT Article.id',
						'Article.title',
						'Article.perex',
						'Article.user_id',
						'Article.seo_title',
						'Article.media_type_id',
						'Article.publish_time',
						'Article.hot_update_time',
						'Article.hot_update',
						'User.id',
						'User.name',
						'User.surname',
						'User.username',
						'Image.id',
						'Image.name',
						'Image.description',
						'Article.article_type_id',
						'Article.status_id',
						'Article.created',
						'Article.modified',
				),
				'order' => array(
						'Article.publish_time' => 'DESC',
						'Article.hot_update_time' => 'DESC',
				),
				'limit' => 10
		);
		
		$articles = $this->paginate('ArticleCategory');
		
		$data = array();
		foreach ($articles as $article){
			$data[$article['Article']['id']] = $article;
		}
		
		$this->loadModel('Comment');
		$comments_count = $this->Comment->find('all', array(
				'fields' => array('Comment.article_id', 'count(Comment.id) as count'),
				'conditions' => array(
						'Comment.article_id' => array_keys($data)
				),
				'group' => array('Comment.article_id')
		));
		
		foreach ($comments_count as $comment){
			$data[$comment['Comment']['article_id']]['comment_count'] = $comment[0]['count'];
		}
		
		$this->set('title_for_layout', '| H&#318;adanie');
		$this->set('articles', $data);
	}
	
	public function advanced_search(){
		$this->set('advanced', true);
		$this->set('title_for_layout', '| Roz&#353;&iacute;ren&eacute; h&#318;adanie');
		$this->render('search');
	}
	
	private function isIpAddressBanned($ip_address){
		$this->loadModel('BannedIp');
		$ipAddress = $this->BannedIp->find('first', array(
				'conditions' => array(
						'BannedIp.ip' => $ip_address
				)
		));
	
		return !empty($ipAddress);
	}
}
