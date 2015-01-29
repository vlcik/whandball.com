<div class="site-title">
		<a href="/"><img src="/img/logo_h.png" height="125">
		</a>

<!--  
	<object type="application/x-shockwave-flash" data="/img/flash/clock.swf" vspace="5" height="120">
		<param name="wmode" value="transparent" />
	</object>
 -->

</div>

<div class="header-social-icons">
<a href="https://twitter.com/whandballcom" target="_blank"><img src="/img/twitter.png" height="45" hspace="5"></a><a href="https://facebook.com/pages/WHandball/174115382767239" target="_blank"><img src="/img/facebook.png"  height="45" hspace="5"></a><!--<a href="https://www.youtube.com/user/WHandballVideo" target="_blank"><img src="/img/youtube.png" height="45" hspace="5"></a> -->
</div>

<div class="search-box">
	<?php 
		$formUrl = Router::url(array(
		    'controller' => 'articles',
		    'action' => 'search',
			'page' => 1
		));

	?>
	<form action="<?php echo $formUrl; ?>" method="get" id="header-search-form">
		<input type="text" placeholder="Hľadanie..."  name="q"
			value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : "";?>"
			class="square" id="header-search-query"> <input type="submit"
			value="Hľadaj!" id="header-search-button">
	</form>
	<div style="padding-left: 5px;">
		<p>
			<?php 
			echo $this->Html->link(
					'Rozšírené hľadanie',
					array(
						'controller' => 'articles',
						'action' => 'advanced_search'
					)
				);
			?>
		</p>
	</div>

</div>


<div class="clear"></div>
<div id="dialog" title="Upozornenie!" style="display: none;">
	<p>
		Hľadaný výraz musí obsahovať min. 3 znaky.
	</p>
</div>
<script>
$( document ).ready(function() {

		$('#header-search-button').click(function(){
			if ($('#header-search-query').val().length < 3){
				$( "#dialog" ).dialog({
					height: 70,
					modal: true
				});
				return false;
			}
			else {
				$('#header-search-form').submit();
			}
		});
});
</script>
