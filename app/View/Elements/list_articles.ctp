<?php 

if (isset($articles)):
	if (count($articles) > 0):
		foreach ($articles as $article):
?>



	<div class="post">
		<div style="height: auto; width: 26%; float: left;">
		<a class="galpop-info" style="text-decoration: none;" 
			data-galpop-group="<?php echo $article['Article']['title'];?>"
			data-galpop-link-title="<?php echo $article['Image']['description'];?>" 
			title="<?php echo $article['Image']['description'];?>"  
			href=<?php echo IMAGE_ROOT_FOLDER_HTML . "/" . $article['Article']['id'] . '/large/' . $article['Image']['name'];?>>
			<?php 
				if (!empty($article['Image']['name'])){
					echo $this->Html->image(IMAGE_ROOT_FOLDER_HTML . "/" . $article['Article']['id'] . '/small/' . $article['Image']['name'], array(
							'class' => array(
									'left', 'bordered'
							)
					));
				}
			
			?>
		</a>
		</div>		

		<div style="height:auto; width: 74%; float: right;">

		<h3 style="margin-bottom: 3px;">
			<?php 
				echo $this->Html->link(
					$article['Article']['title'],
					array(
							'controller' => 'articles',
							'action' => 'show',
							'id' => $article['Article']['id'],
							'seo_title' => $article['Article']['seo_title'],
					),
					array(
							'class' => 'more'
					)
				);
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
					
					echo $this->Html->image($image_path, array('alt' => 'Media type', 'style' => 'position: relative; left: 5px;top:5px;'));
				}
			?>
		</h3>


		<div class="article-info"> 
			<span class="author"> 
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
		<div style="text-align: justify">
		<p>
			<?php 
			echo $article['Article']['perex'];
			?>

		</p>
		</div>

		<div style="float:right;">
		<b>
			<img src="/img/ga-readmore.png" height="12">
			<?php
			echo $this->Html->link(
				__('Celý článok', true),
				array(
						'controller' => 'articles',
						'action' => 'show',
						'id' => $article['Article']['id'],
						'seo_title' => $article['Article']['seo_title'],
				),
				array(
						'class' => ''
				)
			);
		?>
		</b>
			&nbsp; | &nbsp;

			<img src="/img/ga-discussion.png" height="12">
			<?php 
				$count = (isset($article['comment_count'])) ? $article['comment_count']: 0;
				echo $this->Html->link(
						"Diskusia (" . $count . ")",
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $article['Article']['id'],
								'seo_title' => $article['Article']['seo_title'],
								'#' => 'diskusia'
						)
				);
			 ?>
		<br /><br />
		</div>

		<div class="clearer">&nbsp;</div>
	
	</div>
	<div class="content-separator"></div>

	</div>

<?php 
		endforeach;
		
		echo $this->element('pagination');

	else:
?>

	<div>
		<?php 
			echo __('Žiadny záznam.', true);
		?>
	</div>


<?php 
	endif;
endif;
?>