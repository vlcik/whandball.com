<?php 
$url = array(
		'controller' => $this->request->params['controller'],
		'action' => $this->request->params['action'],
		'id' => $this->request->params['id'],
		'name' => $this->request->params['name']
);

if (isset($this->request->params['page'])){
	$url['page'] = $this->request->params['page'];
}
$this->Paginator->options(
		array(
				'url' => $url
		)
);
if (!$is_home):	

?>

<h2>
	<?php
		echo $category['Category']['name'];
	?>
</h2>
<div>
	<span class="category-path"> 
	<?php 
		echo $this->Category->get_category_path($category_path, $category);
	?>
	</span>
</div>
<br />
<?php 

endif;

if (isset($articles)){
	echo $this->element('list_articles', array('articles' => $articles));
}


echo $this->Html->script('galpop/jquery.galpop.js');
echo $this->Html->css('jquery.galpop.css');
echo $this->Html->scriptBlock('
			$(function() {

		    	$(".galpop-info").galpop();
			    
			});
		');

    ?>