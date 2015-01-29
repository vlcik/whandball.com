<?php

    /*
     * CategoryTreeComponent
     * 
     * @author Juraj Vlk juraj.vlk@gmail.com
     */
	App::uses('Component', 'Controller');
    class CategoryTreeComponent extends Component {

    	private $uses = array('Category');
    	private $controller;
    	 
        public function initialize(Controller $controller) {
			$this->controller = $controller;  
	    }    

	   	public function get_category_path($path_array, $category_id) {
	    	if ($category_id != 0){
	    		$this->controller->loadModel('Category');
	    		$category = $this->controller->Category->find('first', array(
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
	    
	    public function get_string_path($path){
	    	$p = "";
	    	$i = 1;
	    	foreach(array_reverse($path) as $_item){
	    		$p .= $_item['Category']['name'];
	    
	    		if ($i++ != count($path)){
	    			$p .= " -> ";
	    		}
	    	}
	    
	    	return $p;
	    }
    }
	
?>