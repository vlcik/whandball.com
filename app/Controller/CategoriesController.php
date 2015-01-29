<?php
App::uses('AppController', 'Controller');

class CategoriesController extends AppController {

	public $name = 'Categories';
	
	public $components = array('RequestHandler', 'CategoryTree');

	public $helpers = array('Form', 'Html', 'Js');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_autocomplete_categories', 'make_slug');
	}

	public function admin_index($parent_id = 1) {
		
		$this->paginate = array(
			'conditions' => array(
					'NOT' => array(
							'Category.status_id' => ARTICLE_DELETED
					),
					'Category.parent_id' => $parent_id
			),
			'fields' => array(
					'Category.id',
					'Category.name',
					'Category.status_id',
					'Category.created',
					'Category.modified',
			),
			'order' => array(
					'Category.modified' => 'ASC'
			),
			'limit' => 10
		);
	
		$this->set('category', $this->Category->find('first', array(
			'conditions' => array(
					'NOT' => array(
							'Category.status_id' => ARTICLE_DELETED
					),
					'Category.id' => $parent_id
			),
			'fields' => array(
					'Category.id',
					'Category.name',
					'Category.status_id',
					'Category.created',
					'Category.modified',
			)
		)));
		$this->set('categories', $this->paginate('Category'));
		
		$this->set('path', $this->Category->getPath($parent_id));
	}
	
	private  function get_children($root_category_id) {
		return $this->Category->find('all', array(
				'fields' => array(
						'Category.id',
						'Category.name',
						'Category.parent_id',
						'Category.created',
				),
				'conditions' => array(
						'Category.parent_id' => $root_category_id
				)
		));
	}
	
	
	private function get_category_path($path_array, $category_id) {
		if ($category_id != 0){
			$category = $this->Category->find('first', array(
					'fields' => array(
							'Category.id',
							'Category.name',
							'Category.parent_id',
							'Category.created',
					),
					'conditions' => array(
							'Category.id' => $category_id
					)
			));
			
			array_push($path_array, $category);
			if ($category['Category']['parent_id'] == 0){
				return $path_array;
			}
			else {
				return $this->get_category_path($path_array, $category['Category']['parent_id']);
			}
				
		}
	}
	
	public function admin_add($parent_id = null) {
		
		if ($parent_id == null){
			$parent_id = $this->request->data['Category']['parent_id'];	
		}
		
		$parent_category = $this->Category->find('first', array(
			'conditions' => array(
				'Category.id' => $parent_id
			)
		));
		
		if (empty($parent_category)){
			throw new NotFoundException();
		}
		
		if (!(empty($this->request->data))){
			$this->request->data['Category']['slug'] = Inflector::slug($this->request->data['Category']['name'], "-");
			$this->Category->set($this->request->data);
			$this->Category->data['Category']['parent_id'] = $parent_category['Category']['id'];
			if ($this->Category->validates()) {
				if ($this->Category->save()) {
					$this->Session->setFlash(__('Category was successfully saved.', true), 'success');
					$this->redirect('/admin/categories/index/' . $parent_category['Category']['id']);
				}
				else {
					$this->Session->setFlash(__('Error occured while saving category. Please try again later.', true), 'error');
				}
			}
		}
		
		$this->set('category', $parent_category);
		$this->set('is_edit', false);
	}
	
	public function admin_edit($id) {
		
		$this->Category->id = $id;
		$category = $this->Category->find('first', array(
				'conditions' => array(
						'Category.id' => $id
				)
		));
	
		if (empty($category)){
			throw new NotFoundException();
		}
	
		if (!(empty($this->request->data))){
			
			$this->request->data['Category']['slug'] = Inflector::slug($this->request->data['Category']['name'], "-");
			$this->Category->set($this->request->data);
			if ($this->Category->validates()) {
				
				if ($this->Category->save()) {
					
					$this->Session->setFlash(__('Category was successfully updated.', true), 'success');
					$this->redirect('/admin/categories/index/' . $category['Category']['parent_id']);
				}
				else {
					$this->Session->setFlash(__('Error occured while saving.', true), 'error');
				}
			}
			else {
				$this->Session->setFlash(__('Error occured while saving.', true), 'error');
			}
		}
	
		$this->request->data = $category;
		$this->set('category', $category);
		$this->set('is_edit', true);
		$this->render('admin_add');
	}
	
	public function get_autocomplete_categories(){
		
		$categories = $this->Category->find('all', array(
				'conditions' => array(
						'Category.name LIKE' => $this->params->query['term'] . "%",
						'Category.status_id' => ITEM_ACTIVE,
						'NOT' => array (
							'Category.id' => ROOT_CATEGORY,
						)
				),
				'fields' => array(
						'Category.id',
						'Category.name'
				)
		));
		$categories_output = array();
		foreach ($categories as $category){
			$cat = array();
			$cat['id'] = $category['Category']['id'];
			$cat['name'] = $category['Category']['name'];
			$cat['path'] = $this->CategoryTree->get_string_path($this->get_category_path(array(), $category['Category']['id']));
			$categories_output[] = $cat;
		}
		$this->set('categories', $categories_output);
	}
	
	function admin_delete($id){
	
		$this->Category->id = $id;
		$category = $this->Category->find('first', array(
				'conditions' => array(
						'Category.id' => $id
				)
		));
	
		if (empty($category)){
			throw new NotFoundException();
		}
	
		if ($this->request->is('get')) {
			if ($this->Category->delete()) {
				$this->Session->setFlash(__('The Category and its subcategories has been removed'), 'success');
				$this->redirect('/admin/categories/index/' . $category['Category']['parent_id']);
			}
			else {
				$this->Session->setFlash(__('The category could not be removed. Please, try again.'), 'error');
			}
		}
	
		$this->render('admin_index');
	
	}
	
	function make_slug(){
		$this->layout = false;
		$categories = $this->Category->find('all');
		foreach ($categories as $category){
			$this->Category->create();
			$this->Category->id = $category['Category']['id'];
			debug($this->Category->saveField('slug', Inflector::slug($category['Category']['name'], "-")));
		}
		
		die();
		
	}
}
