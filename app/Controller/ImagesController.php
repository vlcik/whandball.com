<?php

App::uses('Controller', 'Controller');

class ImagesController extends AppController {

	public $components = array(
			'Upload'
	);

	function beforeFilter() {
		parent::beforeFilter();
	
		$this->Auth->allow('get_description', 'add_description');
	}
	
	public function upload($trans_id){
		$this->Upload->upload($trans_id);
	}

	public function delete($id){
		$image = $this->Image->find('first', array(
				'conditions' => array(
						'Image.id' => $id,
				),
				'recursive' => -1
		));
			
		if (empty($image)){
			throw new NotFoundException('Image: ' . $id . ' was not found');
		}
		else {
			$image['Image']['status_id'] = ITEM_DELETED;

			$this->Image->set($image);

			if ($this->Image->delete($id)){
				$this->layout = false;
				unlink(IMAGE_ROOT_FOLDER . DS. $image['Image']['article_id'] . '/mini/' . $image['Image']['name']);
				unlink(IMAGE_ROOT_FOLDER . DS. $image['Image']['article_id'] . '/small/' . $image['Image']['name']);
				unlink(IMAGE_ROOT_FOLDER . DS. $image['Image']['article_id'] . '/large/' . $image['Image']['name']);
				unlink(IMAGE_ROOT_FOLDER . DS. $image['Image']['article_id'] . '/box/' . $image['Image']['name']);
				unlink(IMAGE_ROOT_FOLDER . DS. $image['Image']['article_id'] . '/original/' . $image['Image']['name']);
					
			}

		}
			
		$this->autoRender = false;
	}

	public function editor_set_main($id){
		$this->admin_set_main($id);
	}

	public function admin_set_main($id, $article_id){
			
		$mains = $this->Image->find('all', array(
				'conditions' => array(
						'Image.image_type_id' => MAIN_ARTICLE_IMAGE,
						'article_id' => $article_id
				),
				'fields' => array(
						'Image.id'
				)
		));
		
		foreach ($mains as $image){
			$this->Image->id = $image['Image']['id'];
			$this->Image->saveField('image_type_id', ORDINARY_ARTICLE_IMAGE);
		}

		$this->Image->id = $id;
		$this->Image->saveField('image_type_id', MAIN_ARTICLE_IMAGE);
		
		$this->layout = false;
		$this->render(false);
	}
	
	public function get_description($id){
		$this->layout = 'ajax';
		$this->loadModel("Image");
		$this->Image->id = $id;
		$image = $this->Image->read("description", $id);
		$this->set('data', $image['Image']['description']);
		//debug($image);
		$this->render('/Elements/json');
	}
	
	public function add_description($id){
		$this->layout = 'ajax';
		$this->loadModel("Image");
		$this->Image->id = $id;
		$image = $this->Image->saveField("description", $this->request->data['description']);
		$this->set('data', array('result' => 1));
	
		$this->render('/Elements/json');
	}
}
