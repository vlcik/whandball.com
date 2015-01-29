<?php 

$_statuses = array(
		ITEM_INACTIVE => __('Inactive', true),
		ITEM_ACTIVE => __('Active', true),
		ITEM_DELETED => __('Deleted', true),
);
?>
<div class="breadcrumbs">
	<p>
		<?php
		echo $this->Html->link(__("Article managment", true), array('controller' => 'articles', 'action' => 'index')) . "&nbsp;>&nbsp;" . $this->Html->link(__('Banned IP addresses management', true), array('controller' => 'bannedIps', 'action' => 'index')) . "&nbsp;>&nbsp;" . __('Add new banned IP address', true);
		?>
		<br /> <br />
	</p>
</div>
<h2>
	<?php 
	echo __('Banned IP addresses management', true);
	?>
</h2>
<?php 
		echo $this->Session->flash();

        echo $this->Form->create('BannedIp', array('controller' => 'bannedips',  'action' => 'add'));

		echo $this->Form->input('ip', array(
	        'label' => __('IP address', true)
	    ));

	    echo $this->Form->submit(__('Save', true));
	    echo $this->Form->end();
?>