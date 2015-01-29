<div class="flash-message error">  
<?php
	$image = $this->Html->image(
    	'problem.png',
        array(
        	'alt' => __('Error', true)
        )
    );
		
	echo $this->Html->para(null, $image . $message, array());
	
?>
</div>