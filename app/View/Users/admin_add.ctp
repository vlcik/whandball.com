<?php
    if ($is_edit):
?>
<div class="breadcrumbs">
    <p>
        <?php
            echo $this->Html->link(__("User management", true), array('controller' => 'users', 'action' => 'index', 'admin' => true)) . "&nbsp;>&nbsp;" . __("Editing user", true);
    ?>
        <br/><br/>
    </p>
</div>

<?php 
    else:
?>

<div class="breadcrumbs">
    <p>
        <?php
            echo $this->Html->link(__("User management", true), array('controller' => 'users', 'action' => 'index', 'admin' => true)) . "&nbsp;>&nbsp;" . __("Pridanie nového používateľa", true);
    ?>
        <br/><br/>
    </p>
</div>

<?php
    endif;
?>

<h2>
    <?php
    	if ($is_edit){
    		echo __("Editing User", true);
    	} 
    	else {
    		echo __("New User", true);
    	}
    ?>
</h2>

<?php

    $_statuses = array(
        ITEM_ACTIVE => __("Active", true),
        ITEM_INACTIVE => __("Inactive", true)
    );
    
    $_roles = array(
        ADMIN => __("Admin", true),
        EDITOR => __("Editor", true)
        
    );

    if ($is_edit){
        echo $this->Form->create('User', array('action' => 'edit'));
    }
    else {
        echo $this->Form->create('User', array('action' => 'add'));
    }

    echo $this->Session->flash();
    
    echo $this->Form->input('id', array(
        'type' => 'hidden'
        
    ));

    echo $this->Form->input('username', array(
        'label' => __('Username', true)
    ));

	echo $this->Form->input('name', array(
			'label' => __('Name', true)
	));

	echo $this->Form->input('surname', array(
			'label' => __('Surname', true)
	));
    
    echo $this->Form->input('email', array(
        'label' => __('Email', true)
    ));

    echo $this->Form->input('status_id', array(
        'options' => $_statuses,
        'empty' => '---',
        'label' => __('Status', true)
    ));
    
    echo $this->Form->input('group_id', array(
        'options' => $_roles,
        'empty' => '---',
        'label' => __('Role', true)

    ));

    echo $this->Form->submit(__('Save', true));
    echo $this->Form->end();
?>