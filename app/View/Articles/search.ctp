
<?php

$url = array(
		'controller' => $this->request->params['controller'],
		'action' => $this->request->params['action']
);

if (isset($this->request->params['page'])){
	$url['page'] = $this->request->params['page'];
}
foreach ($this->request->query as $key => $query){
	$url['?'][$key] = $query;
}
$this->Paginator->options(
		array(
				'url' => $url
		)
);

if (isset($advanced)):
	echo $this->element('advanced_search_form'); 
endif;
?>

<?php
if (isset($this->request->query['q'])):
?>
	<div class="search-results">
	<h2>
		Výsledky hľadania: <font color="#003366"><?php echo $this->request->query['q']; ?></font>
	</h2>
	</div>
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