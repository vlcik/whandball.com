<div id="container" class="clearfix">

	<?php
	$image_main_html_id = "";
	foreach ($images as $image):
	?>
	<!-- <div style="border-style: solid; border-width: 0px;padding: 10px;margin: 10px;text-align: center; font-size: 0.9em;">-->
	<div class="box-gallery photo col3" id="image<?php echo $image['id']?>">
		<?php
		$destination = IMAGE_ROOT_FOLDER . "/" . $image['article_id'] . '/large/' . $image['name'];
		$destination_html = IMAGE_ROOT_FOLDER_HTML . "/" . $image['article_id'] . '/large/' . $image['name'];

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

		echo $this->Js->link(
				__('Description', true),
				array(
						'controller' => 'images',
						'action' => 'get_description',
						$image['id'],
						'admin' => false
				),
				array(
						'success' => "var text = unescape(data.substr(1,data.length - 2)); console.log(text);$('#description').val(text);description_link(" . $image['id'] . ")",
						'class' => "description-link"
				)
		);
		echo "&nbsp;|&nbsp;";
		echo $this->Js->link(
				__('Set title image', true),
				array(
						'controller' => 'images',
						'action' => 'set_main',
						$image['id'],
						$image['article_id']
				),
				array(
						'success' => '$(".box-gallery").css("background-color","#D8D5D2");$("#image' . $image['id'] . '").css("background-color","#00A0F0");',
						'escape' => false,
						'buffer' => ''
				),
				__('Are you sure to delete this image?', true)
		);
		echo "&nbsp;|&nbsp;";

		echo $this->Js->link(
				__('Delete image', true),
				array(
						'controller' => 'images',
						'action' => 'delete',
						$image['id'],
						'admin' => false,
						'editor' => false,
				),
				array(
							'success' => '$("#image' . $image['id'] . '").remove();',
							'escape' => false
						),
						__('Are you sure to delete this image?', true)
		);

		echo $this->Js->writeBuffer();
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

<div id="dialog-form" title="Description of image">
	<input type="hidden" name="image-id" id="image-id"/>
	<input type="text" name="description" id="description" style="width: 780px;" class="text ui-widget-content ui-corner-all"/>
</div>
<div id="dialog-form-success-message" title="Success">
	Description of the image has been submitted.
</div>
<script>

	$( document ).ready(function() {
		$( "#dialog-form" ).dialog({
		    autoOpen: false,
		    height: 150,
		    width: 800,
		    modal: true,
		    buttons: {
			      "Submit description": function() {
			           var bValid = true;
			           $.ajax({
			        	   url: "/images/add_description/" + $('#image-id').val(),
			        	   type: "POST",
			        	   data: { description : $('#description').val() },
			        	   dataType: "html"
			        	});
			           	$( this ).dialog( "close" );
			           	$( "#dialog-form-success-message" ).dialog( "open" );
			      },
			      Cancel: function() {
			        $( this ).dialog( "close" );
			      }
		    },
		    close: function() {
		    }
		});

		$( "#dialog-form-success-message" ).dialog({
		    autoOpen: false,
		    height: 200,
		    width: 300,
		    modal: true,
		    buttons: {
			      "OK": function() {
			    	  $( this ).dialog( "close" );
			      }
		    }
		  });
	
		
		  
	});

	function description_link(image_id) {
		$('#image-id').val(image_id);
		$( "#dialog-form" ).dialog( "open" ); 
	};

	
</script>

