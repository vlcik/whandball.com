<div class="breadcrumbs">
    <p>
        <?php
        	$action = __("Add subcategory", true);
        	$action_link = $this->Html->link(
                $category['Category']['name'],
                array(
                    'controller' => 'categories',
                    'action' => 'index',
					$category['Category']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Add root category', true)
                )
            ); 
        	if ($is_edit){
        		$action = __("Editing category", true);
        	}
        	
            echo $this->Html->link(__("Categories management", true), array('controller' => 'categories', 'action' => 'index', 'admin' => true)) . "&nbsp;>&nbsp;" . $action . " - " . $action_link;
    	?>
        <br/><br/>
    </p>
</div>

<h2>
    <?php
    	echo $action . " - " . $category['Category']['name'];
    ?>
</h2>
<?php 

	echo $this->Session->flash();
	
    $_statuses = array(
        '1' => __("Aktívny", true),
        '0' => __("Neaktívny", true)
        
    );
    
    if ($is_edit){
        echo $this->Form->create('Category', array('action' => 'edit'));
    }
    else {
        echo $this->Form->create('Category', array('action' => 'add'));
    }

    if (!$is_edit){
	    echo $this->Form->input('parent_id', array(
			'type' => 'hidden',
			'default' => $category['Category']['id']
		));	
    }
    
    echo $this->Form->input('name', array(
        'label' => __('Name', true)
    ));
    
    echo $this->Form->input('status_id', array(
        'options' => $_statuses,
        'empty' => '---',
        'label' => __('Status', true)
    ));

    echo $this->Form->submit(__('Save', true));
    echo $this->Form->end();
?>