<div id="container" class="clearfix">

	<div class="h8">
	Najnov&#353;ie koment&aacute;re
	</div>
	
	<ul>
		<?php 
			foreach ($lastComments as $comment):
		?>
		<li>
			<span style="margin-right: 5px;">
				<?php echo $this->Timezone->get_user_time_tz('d.m.Y, H:i', $comment['Comment']['created']);?> &ndash;
			
				<?php 
					$title = $this->Text->truncate(
							$comment['Comment']['content'],
							55,
							array(
									'ellipsis' => '...',
									'exact' => false
							)
					);
					echo "<b>" . $comment['Comment']['name'] . "</b>:<br /> ";
					echo $this->Html->link(
						$title,
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $comment['Article']['id'],
								'seo_title' => $comment['Article']['seo_title'],
								'#' => $comment['Comment']['id']
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
<script>
/*$(function() {
    $( "#tabs" ).tabs();
  });*/
</script>
