
<script language="javascript" type="text/javascript">
function showHide(shID) {
	if (document.getElementById(shID)) {
		if (document.getElementById(shID+'-show').style.display != 'none') {
			document.getElementById(shID+'-show').style.display = 'none';
			document.getElementById(shID).style.display = 'block';
		}
		else {
			document.getElementById(shID+'-show').style.display = 'inline';
			document.getElementById(shID).style.display = 'none';
		}
	}
}
</script>

<div class="post">

	<div class="post-title">
		<h2>
			<?php
				echo $article['Article']['title'];
			?>
			<?php
				if ($article['Article']['media_type_id'] != TEXT_MEDIA_TYPE){
					$image_path = "";
					switch ($article['Article']['media_type_id']){
						case AUDIO_MEDIA_TYPE: 
							$image_path = "audio.png";
							break;
						case VIDEO_MEDIA_TYPE:
							$image_path = "video.png";
							break;
						case PHOTO_MEDIA_TYPE:
							$image_path = "photo.png";
							break;
					}
					
					echo $this->Html->image($image_path, array('alt' => 'Media type', 'style' => 'position: relative; left: 5px;top:3px;'));
				}
			?>
		</h2>
	</div>

	<div class="post-date">
	
		<div class="article-info"> 
			<span> 
			<?php 
				echo $this->Html->link(
					$article['User']['name'] . " " . $article['User']['surname'],
					array(
							'controller' => 'articles',
							'action' => 'user_articles_list',
							'id' => $article['User']['id'],
							'name' => $article['User']['username'],
					)
				);
			?>
			&nbsp;&nbsp;
			<?php 
				echo __('', true);
				echo $this->Timezone->get_user_time_tz('d.m.Y, H:i', $article['Article']['publish_time']);
			?>
			</span>
			<?php 
				if ($article['Article']['hot_update'] == 1){ ?>
				&nbsp;
				<font color="white"><span style="background-color: #FF5050"> 
				<?php 
					echo __('&nbsp;Aktualizované ', true);
					echo $this->Timezone->get_user_time_tz('d.m.Y, H:i', $article['Article']['hot_update_time']);
				?>
				&nbsp;</span></font>
				<?php
				}
			?>
		</div>
	</div>

	<div class="post-body">
		<br/>
		<?php 
			echo $article['Article']['perex'];
		?>
		<br/>
		<?php 
			echo $article['Article']['content'];
		?>
		<br/>

	</div>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
	</div>
	<script type="text/javascript">var addthis_config = {"data_track_addressbar":true,"ui_language":"sk"};</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52cb34782a52b56d"></script>
	<!-- AddThis Button END -->

</div>
<?php 
	if (count($article['RelatedArticle']) > 0):
?>
	<div class="content-separator"></div>
	<div>
		<h7>
			<?php
			echo __('Súvisiace články', true);
			?>
		<br /><br />
		</h7>

		<div>
			<?php 
				$links = "";
				foreach ($article['RelatedArticle'] as $relatedArticle){
					$links .= $this->Html->link(
						$relatedArticle['Article']['title'],
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $relatedArticle['Article']['id'],
								'seo_title' => $relatedArticle['Article']['seo_title'],
						)
					) . "<br/>";
				}
				echo $this->Html->para("", $links);
			?>
		</div>
	</div>
<?php 
	endif;
?>
<div class="content-separator"></div>
<div>
	<?php
		$is_gallery = true;
		if (count($article['Image']) == 0){
			$is_gallery = false;
		}
		else if ((count($article['Image']) == 1) && ($article['Image'][0]['image_type_id'] == MAIN_ARTICLE_IMAGE)){
			$is_gallery = false;
		}
		if ($is_gallery):
	?>
		<h7>
			<?php
			echo __('Galéria', true);
			?>
		</h7>
		<div class="article-box">
			<?php 
				echo $this->element('images', array(
						'images' => $article['Image']
				));
			?>
		</div>
		<div class="content-separator"></div>
	<?php 
		endif;
	?>
</div>
<div>
	<h7>
		<?php
		echo __('Kategórie článku', true);
		?>
	</h7>
	<div class="article-box" style="position: relative; top: 0px;">

<a href="#" id="ShowHideArticleCategories-show" class="showhideShowlink" onclick="showHide('ShowHideArticleCategories');return false;"><br />&#9660; Zobraziť kategórie článku.</a>

		<div class="category-path showhideMorecontent" id="ShowHideArticleCategories">
		<?php
		if (!empty($paths)){
				$p = "";
				foreach ($paths as $path){
	
					$i = 1;
					foreach($path as $_item){
						$p .= $this->Html->link(
							$_item['Category']['name'],
							array(
								'controller' => 'articles',
								'action' => 'get_articles_by_category',
								'id' => $_item['Category']['id'],
								'name' => $_item['Category']['slug']
							)
						);
	
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
				echo $p;
			}
			?>



		</div>
	</div>
</div>
<div class="content-separator"></div>
<div>
	<a name="diskusia">
		<h7>
			<?php
				echo __('Diskusia &#40;', true) . "" . count($article['Comment']) . "" . __('&#41', true);
			?>
		</h7>
	</a>
	<div class="article-box">
		<div class="discussion-form">
			<?php 
			echo $this->Form->create('Comment', array('url' => array('controller' => 'articles', 'action' => 'show', $article['Article']['id'])));
			echo $this->Form->input('article_id', array('type' => 'hidden','default' => $article['Article']['id']));
			echo $this->Form->input('name', array( 'label' => __('Meno', true) ));
			echo $this->Form->input('content', array( 'type' => 'textarea', 'rows'=> 5, 'cols' => 80, 'label' => __('Text komentáru', true) ));
			echo $this->Captcha->input();
			echo $this->Form->submit(__('Odoslať komentár', true), array('id' => 'comment-add-button'));
	
			echo $this->Form->end();
			?>
		</div>
		<div id="dialog-comment-add" title="Upozornenie!" style="display: none;">
			<p>
				Meno sa musí skladať min. z 3 znakov.
			</p>
		</div>
	
		<div class="comments">
			<?php 
			foreach ($article['Comment'] as $comment):
			?>
			<div class="comment" id="<?php echo $comment['id'];?>">
				<h4>
					<?php 
					//echo $comment['title'];
					?>
				</h4>
									<p class="comment-metainfo">

				<b>
				<div style="width: 70%; float:left">

						<?php 
							echo $comment['name'];
						?>
				</div>

				<div style="width: 30%; float:right" align="right">					
	<?php echo $this->Timezone->get_user_time_tz('d.m.Y, H:i', $comment['created']);?>

				</div>

				</b>
				<br />
				<br />

					<?php 
						echo $comment['content'];
					?>
				</p>
			</div>
			<?php 
			endforeach;
			?>
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {

	$('#comment-add-button').click(function(){
		if ($('#CommentName').val().length < 3){
			$( "#dialog-comment-add" ).dialog({
				width: 320,
				height: 70,
				modal: true
			});
			return false;
		}
		else {
			$('#CommentName').submit();
		}
	});
});
</script>

