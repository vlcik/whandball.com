<?php 
if (!empty($this->request->data['Article']['trans_id'])){
	$trans_id = $this->request->data['Article']['trans_id'];
	
}
?>

<div class="breadcrumbs">
	<p>
		<?php
		$breadcrumbs_text = "";
		if ($is_edit) {
			$breadcrumbs_text = __("Edit article", true);
		}
		else {
			$breadcrumbs_text = __("New article", true);
		}

		echo $this->Html->link(__("Article managment", true), array('controller' => 'articles', 'action' => 'index')) . "&nbsp;>&nbsp;" . $breadcrumbs_text;
		?>
		<br /> <br />
	</p>
</div>

<?php 
echo $this->Html->script('jquery-ui/js/jquery-ui-1.10.1.custom.js');
echo $this->Html->css('jquery-ui-1.10.1.custom.css');

echo $this->Html->css('masonry.css');
echo $this->Html->script('masonry/jquery.masonry.js');

echo $this->Html->script("./timepicker/timepicker.js");
echo $this->Html->css("./timepicker/timepicker.css");

echo $this->Html->script("token-input-autocomplete/jquery.tokeninput.js");
echo $this->Html->css("token-input/token-input.css");
echo $this->Html->css("token-input/token-input-facebook.css");

?>
<h2>
	<?php 
	echo $breadcrumbs_text;
	if ($is_edit){
			echo " - '" . $article['Article']['title'] . "'";
		}
		?>
</h2>

<?php
echo $this->Html->script('tinymce/jscripts/tiny_mce/tiny_mce.js');
?>

<div id="tabs">
	<ul>
		<li><a href="#tab-1"> <?php
		echo __("Article", true);
		?>
		</a>
		</li>
		<li id="category-tab"><a href="#tab-2"> <?php
		echo __("Categories", true);
		?>
		</a>
		</li>
		<li id="photogallery-tab">
			<a href="#tab-3"> 
				<?php
					echo __("Photogallery", true);
				?>
			</a>
		</li>
		<li id="sources-tab">
			<a href="#tab-4"> 
				<?php
					echo __("Sources", true);
				?>
			</a>
		</li>
		<li id="related-tab">
			<a href="#tab-5"> 
				<?php
					echo __("Related articles", true);
				?>
			</a>
		</li>
	</ul>
	<div id="tab-1">

		<?php

		if ($is_edit){
	        	echo $this->Form->create('Article', array('action' => 'edit', 'enctype' => 'multipart/form-data'));
	        	 
	        	echo $this->Form->input('id', array(
	        			'type' => 'hidden',
	        	));
	        }
	        else {
	        	echo $this->Form->create('Article', array('action' => 'add', 'enctype' => 'multipart/form-data'));
	        }

	        $_article_types = array(
		        ARTICLE_NON_STATIC => __("Non-static", true),
		        ARTICLE_STATIC => __("Static", true),
		        ARTICLE_BLOCK => __("Block", true),
		    );

		    $_article_statuses = array(
		        ARTICLE_ACTIVE => __("Active", true),
		        ARTICLE_INACTIVE => __("Non-active", true)
		    );

			echo $this->Form->input('trans_id', array(
				'type' => 'hidden',
				'default' => $trans_id
			));

            echo $this->Form->input('title', array(
	'size' => 100,
                'label' => __('Title', true)
            ));

			echo $this->Form->input('media_type_id', array(
					'options' => array(
						TEXT_MEDIA_TYPE => "Text", 
						AUDIO_MEDIA_TYPE => "Audio", 
						PHOTO_MEDIA_TYPE => "Photogallery", 
						VIDEO_MEDIA_TYPE => "Video"
					),
					'label' => __('Media type', true),
					'default' => TEXT_MEDIA_TYPE
				)
			);

			echo $this->Form->input('Article.perex', array(
					'type' => 'textarea',
					'rows' => 5,
					'cols' => 125,
					'label' => __('Perex', true)
			));

            echo $this->Form->input('Article.content', array(
                'type' => 'textarea',
                'rows' => 25,
                'cols' => 125,
                'label' => __('Text', true)
            ));

			if ($is_admin_mode){
				echo $this->Form->input('article_type_id', array(
	                'options' => $_article_types,
	                'label' => __('Article type', true),
	                'empty' => '---'
	            ));

	            echo $this->Form->input('status_id', array(
	                'options' => $_article_statuses,
	                'label' => __('Status', true),
	                'empty' => '---'
	            ));
			}
			else {
				echo $this->Form->input('article_type_id', array(
						'options' => $_article_types,
						'label' => __('Article type', true),
						'default' => ARTICLE_NON_STATIC,
						'disabled' => true
				));

				echo $this->Form->input('status_id', array(
						'options' => $_article_statuses,
						'label' => __('Status', true),
						'default' => ITEM_INACTIVE,
						'disabled' => true
				));
			}
			
			echo $this->Form->input('publish_time', array(
					//'id' => 'ArticlePublishTime',
					//'type' => 'text',
					//'default' => $today['month'] . '/' . $today['mon'] . '/' . $today['year'] 
					'dateFormat' => 'D/M/Y',
					'timeFormat' => '24',
					//'selected' => '0000-00-00 00:00:00'
			));

			if ($is_edit){
				echo $this->Form->label('Article.hot_update', __('Hot update', true), array('style' => 'position: relative; top: -5px;'));
				echo $this->Form->checkbox('hot_update');
				echo "<br/>";
				echo $this->Form->input('hot_update_time', array(
  					'label' => false,
					'dateFormat' => 'D/M/Y',
					'timeFormat' => '24',
  					'maxYear' => date("Y", strtotime("+2 years")),
					'minYear' => date("Y", strtotime("-10 years"))
				));
			}
			?>

	</div>
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			mode : "textareas",
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
	
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
	
			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",
	
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
	
			// Style formats
			style_formats : [
				{title : 'Bold text', inline : 'b'},
				{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
				{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
				{title : 'Example 1', inline : 'span', classes : 'example1'},
				{title : 'Example 2', inline : 'span', classes : 'example2'},
				{title : 'Table styles'},
				{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
			]
		});
	</script>

	<div id="tab-2">
		<?php 
			echo $this->Form->input('category', array(
	    			'id' => 'categories-autocompleter',
	    			'label' => __('Categories', true)
	    	));
        ?>
	</div>

	<div id="tab-3">
		<div id="uploader" style="width: 550px; height: 360px;">You browser
			doesn't support upload.</div>
		<?php

		if ($is_edit){
	    		echo $this->element('gallery_edit', array(
		    	    'images' => $article['Image']
		        ));
			}
			else {
			?>
		<h2>
			<?php echo __('Images already uploaded', true) . ': '; ?>
		</h2>
		<?php 
		if (!empty($this->request->data['Article']['trans_id'])){
					if (is_dir(TEMP_UPLOAD_FOLDER_PATH)){
						$tmpUploadFolderPath = TEMP_UPLOAD_FOLDER_PATH . DIRECTORY_SEPARATOR . $trans_id;
						if (is_dir($tmpUploadFolderPath)){
							$items = $fileComponent->scanFolder($tmpUploadFolderPath);
							$images = array();
							foreach ($items['files'] as $item){
								$image = array();
								$image['name'] = $item;
								$image['trans_id'] = $trans_id;

								$images[] = $image;
							}

							echo $this->element('gallery', array(
								'images' => $images
							));
						}
					}
				}

			}
			?>

	</div>
	<div id="tab-4">
		<!-- <div id="sources">
			<div class="sources-line">
				<label><b>Description: </b></label>
				<input autocomplete="off" class="span3" name="ArticleDescription[]" type="text"/>
				<label><b>URL: </b></label>
				<input autocomplete="off" class="span3" name="ArticleUrl[]" type="text"/>
				<img src="/img/add.png" class="add-more" alt="Add new record of article sources" />
			</div>
		</div>-->
		<div class="container">
	<div class="row">
		<input type="hidden" name="count" value="1" />
        <div class="control-group" id="fields">
            <label class="control-label" for="field1">Nice Multiple Form Fields</label>
            <div class="controls" id="profs"> 
                <div class="input-append">
                	<input autocomplete="off" class="span3" id="field1" name="description[1]" type="text" placeholder="Type something (it has typeahead too)" data-provide="typeahead" data-items="8" data-source='["Aardvark","Beatlejuice","Capricorn","Deathmaul","Epic"]'/>
                    
                    <button id="b1" class="btn btn-info add-more" type="button">
                    	+
                    </button>
                </div>
            <br>
            <small>Press + to add another form field :)</small>
            </div>
        </div>
	</div>
</div>
	</div>
	<div id="tab-5">
	<?php 
			echo $this->Form->input('related_articles', array(
	    			'id' => 'related-articles-autocompleter',
	    			'label' => __('Related articles', true)
	    	));
        ?>
    </div>
		
</div>
<?php
echo $this->Form->submit(__('Save', true));
echo $this->Form->end();

$pre_populate_autocompleter = ($is_edit) ? ",prePopulate:" . $autocompleter_edit : "";
$pre_populate_autocompleter_related_articles = ($is_edit) ? ",prePopulate:" . $autocompleter_edit_related_articles : "";

?>
<script type="text/javascript">

	$(function() {
		$( "#tabs" ).tabs();
		
		$("#ArticlePublishTime").datetimepicker({
			format: "dd/mm/YY",
			timeFormat: "HH:mm:ss",
		});

		$("#photogallery-tab").click(function() {
			var container = $("#container");
	
			container.imagesLoaded( function(){
				container.masonry({
					itemSelector : ".box-gallery",
					isAnimated: true
				});
			});
		});
			
		$("#categories-autocompleter").tokenInput("/categories/get_autocomplete_categories", {
			preventDuplicates: true,
			resultsFormatter: function(item){
				return '<li style="border-bottom: 1px solid #999999;">' + item.name + '<br/>' + item.path + '</li>'
			}
			<?php echo $pre_populate_autocompleter ?>
		});
		
		$("#related-articles-autocompleter").tokenInput("/articles/get_related_articles", {
			preventDuplicates: true,
			resultsFormatter: function(item){
				return '<li style="border-bottom: 1px solid #999999;">' + item.title + '</li>'
			},
			propertyToSearch: 'title'
			<?php echo $pre_populate_autocompleter_related_articles ?>
		});
				
	});
</script>	
<?php
echo $this->Html->script("http://bp.yahooapis.com/2.4.21/browserplus-min.js");
echo $this->Html->script("./plupload/js/plupload.js");
echo $this->Html->script("./plupload/js/plupload.gears.js");
echo $this->Html->script("./plupload/js/plupload.silverlight.js");
echo $this->Html->script("./plupload/js/plupload.flash.js");
echo $this->Html->script("./plupload/js/plupload.browserplus.js");
echo $this->Html->script("./plupload/js/plupload.html4.js");
echo $this->Html->script("./plupload/js/plupload.html5.js");
echo $this->Html->script("./plupload/js/jquery.plupload.queue/jquery.plupload.queue.js");
//echo $this->Html->script("upload.js");
echo $this->Html->css("jquery.plupload.queue.css");

?>
<script type="text/javascript">
$(document).ready(function(){
	function log() {
		var str = "";

		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = 'QUEUED';
								break;

							case plupload.UPLOADING:
								value = 'UPLOADING';
								break;

							case plupload.FAILED:
								value = 'FAILED';
								break;

							case plupload.DONE:
								value = 'DONE';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? ', ': '') + key + '=' + value;
					}
				});

				str += row + " ";
			} else { 
				str += arg + " ";
			}
		});

	}

	$("#uploader").pluploadQueue({
		// General settings
		runtimes: 'html5,gears,browserplus,silverlight,flash,html4',
		url: '/images/upload/<?php echo $trans_id; ?>',
		max_file_size: '10mb',
		chunk_size: '1mb',
		unique_names: true,

		// Resize images on clientside if we can
		//resize: {width: 320, height: 240, quality: 100},

		// Specify what files to browse for
		filters: [
			{title: "Image files", extensions: "jpg,gif,png"}
		],

		// Flash/Silverlight paths
		flash_swf_url: '../../js/plupload.flash.swf',
		silverlight_xap_url: '../../js/plupload.silverlight.xap'
	});
	
	/*var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        e.currentTarget.remove();
        var addto = ".sources-line";
        var newIn = '<div class="sources-line"><label><b>Description: </b></label><input autocomplete="off" class="span3" name="ArticleDescription[]" type="text"/>&nbsp;<label><b>URL: </b></label><input autocomplete="off" class="span3" name="ArticleUrl[]" type="text"/>&nbsp;<img src="/img/add.png" class="add-more" alt="Add new record of article sources" /></div>';
        var newInput = $(newIn);
        $(addto).after(newInput);
        
    });*/var next = 1;
    $("body").on("click", ".add-more", function(e){
        e.preventDefault();
        var addto = "#field" + next;
        next = next + 1;
        var newIn = '<br /><input autocomplete="off" class="span3" id="field' + next + '" name="description[' + next + ']" type="text" data-provide="typeahead" data-items="8">';
        var newInput = $(newIn);
        $(addto).after(newInput);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);  
    });
    
});
</script>