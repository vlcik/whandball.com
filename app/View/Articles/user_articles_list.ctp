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
?>
<h2>
	Články autora 
	<font color="#003366">
	<?php
		echo $user['User']['name'] . " " . $user['User']['surname'];
	?>
	</font>
</h2>

<br />
<?php 


if (isset($articles)){

	echo $this->element('list_articles_by_user', array('articles' => $articles));
}

echo $this->Html->script('galpop/jquery.galpop.js');
echo $this->Html->css('jquery.galpop.css');
echo $this->Html->scriptBlock('
			$(function() {

		    	$(".galpop-info").galpop();
			    
			});
		');
    ?>