
<script>
	$( function()
	{
		$( '#nav li:has(ul)' ).doubleTapToGo();
	});
</script>




<br /><center>
<nav id="nav" role="navigation" style="width:98%";>
	<a href="#nav" title="Show navigation">Show navigation</a>
	<a href="#" title="Hide navigation">Hide navigation</a>
	<ul class="clearfix">

		<li class="first" style="width:13px;"><img height="100%" width="100%" src="/img/menu/left.png"></li>
	
		<li style="width:70px;"><a href="/"><img height="30" src="/img/menu/homepage_out.png" ></a></li>


		<li style="width:140px;">
			<a  aria-haspopup="true"><span><img height="33" src="/img/menu/magazin_out.png" ></span></a>
			<ul>
				<li><a href="/kategoria/45-Aktuality">Aktuality</a></li>
				<li><a href="/kategoria/47-Postrehy">Postrehy</a></li>
				<li><a href="/kategoria/46-Reportaze">Report&aacute;&#382;e</a></li>
				<li><a href="/kategoria/49-Rozhovory">Rozhovory</a></li>
				<li><a href="/kategoria/48-Profily">Profily</a></li>
				<li><a href="/kategoria/50-Historia">Hist&oacute;ria</a></li>

			</ul>
		</li>


		<li style="width:140px;">
			<a aria-haspopup="true"><span><img height="33" src="/img/menu/regiony_out.png"></span></a>
			<ul>
				<li><a href="/kategoria/34-Slovensko">Slovensko</a></li>
				<li><a href="/kategoria/35-Europa">Eur&oacute;pa</a></li>
				<li><a href="/kategoria/37-Afrika">Afrika</a></li>
				<li><a href="/kategoria/36-Azia">&Aacute;zia</a></li>
				<li><a href="/kategoria/38-Panamerika">Panamerika</a></li>
				<li><a href="/kategoria/39-Oceania">Oce&aacute;nia</a></li>
				<li><a href="/kategoria/40-Svet">Svet</a></li>
			</ul>
		</li>
		
		
		<li style="width:150px;">
			<a aria-haspopup="true"><span><img height="33" src="/img/menu/podujatia_out.png"></span></a>
			<ul>
				<li><a href="/kategoria/52-Reprezentacie">Reprezent&aacute;cie</a></li>
				<li><a href="/kategoria/53-Narodne-klubove">N&aacute;rodn&eacute; klubov&eacute;</a></li>
				<li><a href="/kategoria/54-Medzinarodne-klubove">Medzin&aacute;rodn&eacute; klubov&eacute;</a></li>
			</ul>
		</li>
		
		
		<li style="width:160px;">
			<a aria-haspopup="true"><span><img height="33" src="/img/menu/generacie_out.png" ></span></a>
			<ul>
				<li><a href="/kategoria/51-Seniorky">Seniorky</a></li>
				<li><a href="/kategoria/55-Mladez">Ml&aacute;de&#382;</a></li>
			</ul>
		</li>


		<li style="width:140px; ">
			<a aria-haspopup="true"><span><img height="31" src="/img/menu/[+]_out.png" ></span></a>
			<ul>
				<li><a href="/aktualne-reprezentacne-podujatia">Aktu&aacute;lne reprezenta&#269;n&eacute; podujatia</a></li>
				<li><a href="/kalendar-reprezentacnych-podujati">Kalend&aacute;r reprezenta&#269;n&yacute;ch podujat&iacute;</a></li>
			</ul>
		</li>

		<li class="last" style="width:13px;"><img height="100%" width="100%" src="/img/menu/right.png"></li>

	</ul>
</nav>
</center>



 <!-- 
<ul class="tabbed">
	<?php 
	foreach ($items as $item){
		?>
	<li><?php 
	echo $this->Html->link(
			$item['Category']['name'],
			array(
					"controller" => "articles",
					"action" => "get_articles_by_category",
					"id" => $item['Category']['id'],
					"name" => $item['Category']['slug']
			)
	);
	?>
	</li>
	<?php  
	}
	?>

</ul>
<div class="clearer">&nbsp;</div>
-->

