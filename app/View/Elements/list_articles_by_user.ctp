<?php 

if (isset($articles)):
	if (count($articles) > 0):
		foreach ($articles as $article):
?>



	<div class="post">

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