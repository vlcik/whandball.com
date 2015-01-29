<?php

App::uses('Controller', 'Controller');

class BannedIpsController extends AppController {
    
	public $name = 'BannedIps';
    
    public $uses = array('BannedIp'); 

    function beforeFilter() {
    	parent::beforeFilter();
    }
  	
  	public function admin_index(){
  		$this->paginate = array(
  				'conditions' => array(
  						'NOT' => array(
  								'BannedIp.status_id' => ITEM_DELETED
  						),
  				),
  				'fields' => array(
  						'BannedIp.id',
  						'BannedIp.ip',
  						'BannedIp.status_id',
  						'BannedIp.created',
  						'BannedIp.modified',
  				),
  				'order' => array(
  						'BannedIp.modified' => 'ASC'
  				),
  				'limit' => 10
  		);
  		
  		$this->set('bannedIps', $this->paginate('BannedIp'));
    }
    
    public function admin_add() {
    
    	if (!(empty($this->request->data))){
    		$this->BannedIp->set($this->request->data);
    		if ($this->BannedIp->validates()) {
    			if ($this->BannedIp->save()) {
    				$this->Session->setFlash(__('IP address was successfully added.', true), 'success');
    				$this->redirect('/admin/bannedIps');
    			}
    			else {
					$this->Session->setFlash(__('Error occured while saving IP address. Please try again later.', true), 'error');		
    			}
    		}
    	}
    }
    
    function admin_delete($id){
    
    	$this->BannedIp->id = $id;
    
    	if (!$this->BannedIp->exists()) {
    		throw new NotFoundException(__('Invalid article'));
    	}
    
    	if ($this->request->is('get')) {
    		
    		if ($this->BannedIp->delete()) {
    			$this->Session->setFlash(__('The banned IP address has been removed'), 'success');
    			$this->redirect('/admin/bannedIps');
    		}
    		else {
    			$this->Session->setFlash(__('The banned IP address could not be removed. Please, try again.'), 'error');
    		}
    	}
    
    	$this->redirect('/admin/ipaddresses');
    
    }
}
