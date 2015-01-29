<div id="container" class="clearfix">

	<div class= "h8">
	Najdiskutovanej&#353;ie &#269;l&aacute;nky
	</div>

	<ul>
		<?php 
			$i = 1;
			foreach ($mostCommented as $comment):
		?>
		<li>
			<span style="margin-right: 5px;">
				<?php echo $i++;?>.
			</span>
			<span>
				<?php 
				
					echo $this->Html->link(
						$comment['Article']['title'],
						array(
								'controller' => 'articles',
								'action' => 'show',
								'id' => $comment['Article']['id'],
								'seo_title' => $comment['Article']['seo_title'],
						)
				);
				?>
				(<?php echo $comment[0]['cnt']?>)
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
