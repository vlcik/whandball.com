<div id="container" class="clearfix">

	<div class="h8">
	Naposledy diskutovan&eacute; &#269;l&aacute;nky
	</div>

	<ul>
		<?php 
			$i = 1;
			foreach ($lastCommentedArticles as $article):
		?>
		<li>
			<span style="margin-right: 5px;">
				<?php echo $this->Timezone->get_user_time_tz('d.m.Y, H:i', $article['Article']['last_comment_time']);?><br>
				<?php 
					
					echo $this->Html->link(
						$article['Article']['title'],
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $article['Article']['id'],
								'seo_title' => $article['Article']['seo_title'],
								'#' => 'diskusia'
						)
				);
				?>
			</span> 
		</li>
		<?php 	
			endforeach;
		?>
	</ul>	
</div>