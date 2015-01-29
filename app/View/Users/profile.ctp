<h2>
<?php 
	echo __('My profile', true);
?>
</h2>

<?php
    $_statuses = array(
		ITEM_ACTIVE => __("Active", true),
		ITEM_INACTIVE => __("Non-active", true),
		ITEM_DELETED => __("Deleted", true),
	);

    echo $this->Form->create('User', array('action' => 'profile'));
    
    echo $this->Session->flash();
    
    echo $this->Form->input('username', array(
    		'label' => __('Name', true),
    		'error'	=> __('A username is required.', true),
    ));
    
    echo $this->Form->input('email', array(
        'label' => __('Email', true),
    	'error'	=> __('A correct email is required.', true),
    ));

    echo $this->Form->input('id', array(
        'type' => 'hidden'
    ));

    echo $this->Form->input('status_id', array(
        'options' => $_statuses,
        'label' => __('Status', true),
        'error'	=> __('A status is required.', true),
        'disabled' => 'disabled',
    ));

    echo $this->Form->input('oldpass', array(
        'label' => __('Old password', true),
        'type' => 'password'
    ));
    
    echo $this->Form->input('newpass', array(
        'label' => __('New password', true),
        'type' => 'password'
    ));

    echo $this->Form->input('newpass2', array(
        'label' => __('New password confirmation', true),
        'type' => 'password'
    ));

    echo $this->Form->submit(__('Save', true));
    echo $this->Form->end();
?>