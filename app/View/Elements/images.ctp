<div id="photo-gallery">
	
<?php 
	$i = 0;
	$count = count($images);
	foreach ($images as $image):
		if ($image['image_type_id'] == MAIN_ARTICLE_IMAGE && $count <= 2){
			continue;
		}
?>
		<a class="galpop-info" style="text-decoration: none;" 
			data-galpop-group="info"  
			data-galpop-link-title="<?php echo $image['description'];?>" 
			title="<?php echo $image['description'];?>"  
			href=<?php echo IMAGE_ROOT_FOLDER_HTML . "/" . $image['article_id'] . '/large/' . $image['name'];?>>
				<img
				src="<?php echo $destination = IMAGE_ROOT_FOLDER_HTML . "/" . $image['article_id'] . '/small/' . $image['name'];?>"
				style="width:110px; height:73px; border: 4px solid rgba(0, 51, 102, .2); margin: 2px 3px;" 
				onmouseover="this.style.border='4px solid rgba(0, 51, 102, .95)';" 
				onmouseout="this.style.border='4px solid rgba(0, 51, 102, .2)';"/>
		</a>
<?php 
	$i++;
	endforeach;
?>
</div>
<?php 
echo $this->Html->script('galpop/jquery.galpop.js');
echo $this->Html->css('jquery.galpop.css');
echo $this->Html->scriptBlock('
			$(function() {

		    	$(".galpop-info").galpop();
			    
			});
		');
    ?>