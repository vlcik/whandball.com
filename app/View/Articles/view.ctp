<?php 
$_statuses = array(
		ITEM_ACTIVE => __("Active", true),
		ITEM_INACTIVE => __("Non-active", true),
		ITEM_DELETED => __("Deleted", true),
);
?>

<div class="breadcrumbs">
	<p>
		<?php
		echo $this->Html->link(__("Article managment", true), array('controller' => 'articles', 'action' => 'index')) . "&nbsp;>&nbsp;" . __("Article", true);
		?>
		<br /> <br />
	</p>
</div>

<?php 
echo $this->Html->script('jquery-ui/js/jquery-ui-1.10.1.custom.js');
echo $this->Html->css('jquery-ui-1.10.1.custom.css');
echo $this->Html->css('masonry.css');
echo $this->Html->script('masonry/jquery.masonry.js');

echo $this->Html->scriptBlock('
		$(function() {
		    $( "#tabs" ).tabs();

			$("#photogallery-tab").click(function() {
	            var container = $("#container");

	            container.imagesLoaded( function(){
		            container.masonry({
		                itemSelector : ".box-gallery",
						isAnimated: true
});
});
});
});
	');
?>

<h2>
	<?php 
	echo $article['Article']['title'];
	?>
</h2>

<table class="info-table">

	<tr>
		<td class="label"><b> <?php 
		echo __('ID', true);
		?> :
		</b>
		</td>
		<td class="category-path"><?php 
		echo $article['Article']['id'];
		?>
		</td>
	</tr>

	<tr>
		<td class="label"><b> <?php 
		echo __('Status', true);
		?> :
		</b>
		</td>
		<td class="category-path"><?php
		$color = "red";
		if ($article['Article']['status_id'] == ITEM_ACTIVE){
		               $color = "green";
		            }
		            echo '<span style="color:' . $color . '">' . strtr($article['Article']['status_id'], $_statuses) . '</span>';
		            ?>
		</td>
	</tr>

	<tr>
		<td class="label"><b> <?php 
		echo __('Created', true);
		?> :
		</b>
		</td>
		<td class="category-path"><?php 
		echo $this->Time->format('j/m/Y, H:i:s', $article['Article']['created'], null);
		?>
		</td>
	</tr>

	<tr>
		<td class="label"><b> <?php 
		echo __('Last modified', true);
		?> :
		</b>
		</td>
		<td class="category-path"><?php 
		echo $this->Time->format('j/m/Y, H:i:s', $article['Article']['modified'], null);
		?>
		</td>
	</tr>

	<?php 
	if (!empty($paths)){
				$p = "";
				foreach ($paths as $path){
		
					$i = 1;
					foreach(array_reverse($path) as $_item){
						$p .= $_item['Category']['name'];

						if ($i++ != count($path)){
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
					$p .= "<br/>";
				}
				?>
	<tr>
		<td class="label"><b> <?php 
		echo __('Categories', true);
		?> :
		</b>
		</td>
		<td class="category-path"><?php 
		echo $p;
		?>
		</td>
	</tr>
	<?php 
			}
			?>
</table>

<div id="tabs">
	<ul>
		<li><a href="#tab-1"> <?php
		echo __("Article", true);
		?>
		</a>
		</li>
		<li id="photogallery-tab"><a href="#tab-2"> <?php
		echo __("Photogallery", true);
		?>
		</a>
		</li>

	</ul>
	<div id="tab-1">

		<?php

		echo $article['Article']['content'];

		?>

	</div>
	<div id="tab-2">

		<?php
		echo $this->element('gallery', array(
	                'images' => $article['Image']
	            ));
	        ?>

	</div>

</div>
