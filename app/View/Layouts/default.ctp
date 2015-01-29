<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<meta http-equiv="Expire" content="now" />
<meta http-equiv="Pragma" content="no-cache" />
<?php 
echo $this->Html->charset();
?>
<meta http-equiv="Content-Style-Type" content="text/css" />

<link rel="shortcut icon" href="<?php echo $this->base; ?>/favicon.ico"
	type="image/x-icon" />
<title>WHandball <?php echo $title_for_layout;?></title>

<?php

	echo $this->Html->css("style.css");
	echo $this->Html->css("jquery-ui-1.10.1.custom");
	echo $this->Html->script("jquery-ui/js/jquery-1.9.1.js");
	echo $this->Html->script("jquery-ui/js/jquery-ui-1.10.1.custom");

	//echo $this->Html->css("vertcarous_carousel_ver.css");
	//echo $this->Html->script("vertcarousel/vertcarous_jquery.min.js");
	//echo $this->Html->script("vertcarousel/vertcarous_jqcarousel.js");
	//echo $this->Html->script("vertcarousel/vertcarous_carousel_ver.js");

	echo $this->Html->script("slimscroll/jquery.slimscroll.js");
	echo $this->Html->script("slimscroll/prettify.slimscroll.js");
	echo $this->Html->css("prettify.slimscroll.css");

	echo $this->Html->script("navmenu_doubletaptogo.js");
	echo $this->Html->css("navmenu_doubletaptogo.css");


?>


<body id="top">

	<div id="site">
		<div class="center-wrapper">

			<div class="header">

				<?php echo $this->element('header');?>

			</div>
			
			<div class="clear"></div>
			

			<div id="navigation">
				<div id="sub-nav">
					<?php 
						echo $this->element('menu', array(
								'items' => $menu_items
						));
					?>
				</div>
			</div>

			<!-- 
			-->
			<div id="announcement">
				<?php 
						echo $this->element('announcement');
					?>
			</div>


			<div class="main" id="main-two-columns">

				<div class="left" id="main-left">
					<?php echo $this->Session->flash(); ?>
                    <?php echo $content_for_layout; ?>
				</div>

				<div class="right sidebar" id="sidebar">


				<!-- 
					<div class="right-container" id="current-tournaments">
						<?php 
							echo $this->element('current_tournaments');
						?>
					</div>

				-->


					<div class="right-container" id="twitter-feed"><a name="twitter-feed"></a>
	<div class="h8">
	<img src="/img/twitter-mini.png">
	Twitter
	</div>

<a class="twitter-timeline" height="340" href="https://twitter.com/whandballcom"  data-widget-id="345292633398190081" data-chrome="noheader nofooter transparent" >Twitter @whandballcom</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	
					</div>
					
					<div class="right-container">
<?php 
echo $this->element('last_commented_articles', array('lastCommentedArticles' => $lastCommentedArticles));
?>
					</div>

<!--  
					<div class="right-container">
<?php 
echo $this->element('last_comments', array('lastComments' => $lastComments));
?>
					</div>
 -->					

					<div class="right-container">
						<?php 
							echo $this->element('most_commented', array('mostCommented' => $mostCommented));
						?>
					</div>
					
					<div class="right-container" id="next-tournaments">

						<?php 
							echo $this->element('next_tournaments');
						?>

					</div>

					
				</div>

				<div class="clearer">&nbsp;</div>

			</div>

			<div id="footer">

				<?php 
					echo $this->element('footer');
				?>
				
				<?php echo $this->element('sql_dump');?>

				<img src="http://toplist.sk/dot.asp?id=1253658" border="0" width="1" height="1"/>

			</div>

		</div>
	</div>

</body>
</html>
