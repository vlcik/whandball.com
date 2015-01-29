<?php 

if (!$is_home):

	
?>

<h2>
	<?
	echo $category['Category']['name'];
	?>
</h2>
<div>
	<span class="category-path"> 
	<?php 
		echo $this->Category->get_category_path($category_path, $category);
	?>
	</span>
</div>
<br />
<?php 
endif;

if (count($articles) > 0):
	foreach ($articles as $article):
	?>


	<div class="post">
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
		
		<h3>
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
				echo date("d.m.Y, H:i", strtotime($article['Article']['publish_time']));
			?>
			</span>
			<?php 
				if ($article['Article']['hot_update'] == 1){ ?>
				&nbsp;
				<span style="color: red;"> 
				<?php 
					echo __('Aktualizované ', true);
					echo date("d.m.Y, H:i", strtotime($article['Article']['hot_update_time']));
				?>
				</span>
				<?php
				}
			?>
		</div>
		<div>
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
				__('Read more', true),
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
						"Počet komentárov: " . $count,
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $article['Article']['id'],
								'seo_title' => $article['Article']['seo_title'],
								'#' => 'diskusia'
						)
				);
			 ?>
		</div>

		<div class="clearer">&nbsp;</div>
	
	</div>
	<div class="content-separator"></div>
	<?php 
	endforeach;
	?>
<?php 
else:
?>
	<div>
		<?php 
			echo __('&#381;iadny z&aacute;znam.', true);
		?>
	</div>
<?php 
endif;

echo $this->element('pagination'); 

echo $this->Html->script('galpop/jquery.galpop.js');
echo $this->Html->css('jquery.galpop.css');
echo $this->Html->scriptBlock('
			$(function() {

		    	$(".galpop-info").galpop();
			    
			});
		');
    ?>