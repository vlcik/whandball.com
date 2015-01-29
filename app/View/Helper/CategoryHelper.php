
<?php 
App::uses('AppHelper', 'View/Helper');

class CategoryHelper extends AppHelper {
	 
	public $helpers = array('Js', 'Html', 'Form');

	public function get_category_path($category_path = array(), $category){
		$p = "";
		if (count($category_path) > 0){
			if (!is_null($category_path)){
				$i = 1;
				foreach($category_path as $_item){
					if ($category['Category']['id'] != $_item['Category']['id']){
						$p .= $this->Html->link(
								$_item['Category']['name'],
								array(
										'controller' => 'articles',
										'action' => 'get_articles_by_category',
										'id' => $_item['Category']['id'],
										'name' => $_item['Category']['slug'],
										'page' => 1
								),
								array(
										'escape' => false,
										'title' => __('', true)
								)
						);
					}
					else {
						$p .= $_item['Category']['name'];
					}

					if ($i++ != count($category_path)){
						$p .= $this->Html->image(
								'arrow-to-right.png',
								array(
										'width' => 15,
										'height' => 15,
										'class' => 'arrow',
										'alt' => __('arrow to right', true)
								)
						);
					}
				}
			}
		}
		 
		return $p;
	}
}
?>