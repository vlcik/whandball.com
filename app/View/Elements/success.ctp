<div class="flash-message success">  
<?php
	$image = $this->Html->image(
    	'ok.png',
        array(
        	'alt' => __('Success', true)
        )
    );
		
	echo $this->Html->para(null, $image . $message, array());
	
?>
</div>