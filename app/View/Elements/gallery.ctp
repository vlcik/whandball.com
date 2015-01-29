<div id="container" class="clearfix">

        <?php
        	$image_main_html_id = "";
	        foreach ($images as $image):
        ?>
            <!-- <div style="border-style: solid; border-width: 0px;padding: 10px;margin: 10px;text-align: center; font-size: 0.9em;">-->
			<div class="box-gallery photo col3" id="image<?php echo $image['id']?>">
                <?php
                	$destination = "";
                	$destination_html = ""; 
                	if (empty($image['trans_id'])){
                		$destination = IMAGE_ROOT_FOLDER . "/" . $image['article_id'] . '/large/' . $image['name'];
                		$destination_html = IMAGE_ROOT_FOLDER_HTML . "/" . $image['article_id'] . '/large/' . $image['name'];
                	}
                	else {
                		$destination = IMAGE_ROOT_FOLDER . "/" . 'upload/' . $image['trans_id'] . "/" . $image['name'];
                		$destination_html = IMAGE_ROOT_FOLDER_HTML . "/" . 'upload/' . $image['trans_id'] . DS . $image['name'];
                	}
                	
                	$info = getimagesize($destination);
                	$width = $info[0];
                	$height = $info[1];
                	if (($width > BASE_IMAGE_SIZE) || ($height > BASE_IMAGE_SIZE)){
                		$coeficient = ($width > BASE_IMAGE_SIZE) ? $width / BASE_IMAGE_SIZE : /*$height / BASE_IMAGE_SIZE*/1; 
                		
                		$width = $width / $coeficient;

                		$height = $height / $coeficient; 
                	}
                	
                	if ($image['image_type_id'] == MAIN_ARTICLE_IMAGE){
                		$image_main_html_id = "#image" . $image['id'];
                	}
                	
                    echo $this->Html->image($destination_html, array(
                        'alt' => (!empty($image['description'])) ? $image['description'] : "",
                        'height' => $height,
                        'width' => $width,
                    ));
                ?>

            </div>
        <?php
        endforeach;
        ?>
</div>
<?php 
	if ($image_main_html_id != ""){
		echo $this->Html->scriptBlock('
			$("#photogallery-tab").click(function() {
				$("' . $image_main_html_id . '").css("background-color","#00A0F0");
			});
		');
	}
	
?>