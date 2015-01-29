<h2>
	<?php 
		echo __('User managment', true);
	?>
</h2>
<?php 
	echo $this->Session->flash();
?>
<div class="page-offer">
	<?php
		echo $this->Html->image(
            'add.png',
            array(
                'alt' => __('Add user', true)
            )
        );
		echo $this->Html->link(
                __("Add user", true),
                array(
                    'controller' => 'users',
                    'action' => 'add'
                ),
                array(
                    'escape' => false,
                    'title' => __('Add user', true)
                )
            ) 
	?>
</div>
<table cellspacing="0">
    <thead>
        <tr>
            <th class="first"><?php echo $this->Paginator->sort('User.id', 'ID') ?></th>
            <th><?php echo $this->Paginator->sort('User.username', __('Username', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.name', __('Name', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.surname', __('Surname', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.email', __('Email', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.group_id', __('Role', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.status_id', __('Status', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.created', __('Created', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.modified', __('Modified', true)) ?></th>
            <th colspan="2" class="last"><?php echo __('Action') ?></th>
        </tr>
    </thead>
    <tbody>
<?php
    // Možné stavy používateľa
        $_statuses = array(
	        ITEM_ACTIVE => __("Active", true),
	        ITEM_INACTIVE => __("Non-active", true),
        	ITEM_DELETED => __("Deleted", true),
	    );
        
        $_roles = array(
        	ADMIN => __("Admin", true),
        	EDITOR => __("Editor", true)
    	);

        foreach($editors as $_item) {
            
            unset($_item['User']['password']);
        	
            $color = "red";
			if ($_item['User']['status_id'] == ITEM_ACTIVE){
               $color = "green";
            }
            $_item['User']['status_id'] = '<span style="color:' . $color . '">' . strtr($_item['User']['status_id'], $_statuses) . '</span>';
            

			$_item['User']['group_id'] = $_roles[$_item['User']['group_id']];
			$_item['User']['created'] = $this->Time->format('j/m/Y, H:i:s', $_item['User']['created'], null);
			$_item['User']['modified'] = $this->Time->format('j/m/Y, H:i:s', $_item['User']['modified'], null);
			
            $_item['User']['action'] = $this->Html->link(
                $this->Html->image(
                    'edit.png',
                    array(
                        'alt' => __('Upraviť', true)
                    )
                ),
                array(
                    'controller' => 'users',
                    'action' => 'edit',
                    'admin' => true,
                    $_item['User']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Upraviť', true)
                )
            ) 
            . 
            " &nbsp;&nbsp;" 
            . $this->Html->link(
                $this->Html->image(
                    'delete.png',
                    array(
                        'alt' => __('Odstrániť', true)
                    )
                ),
                array(
                    'controller' => 'users',
                    'action' => 'delete',
                    'admin' => true,
                    $_item['User']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Odstrániť', true)
                ),
                __("Do you really want to delete user: ", true) . " " . $_item['User']['username'] . " ?"
                
            );

            // Výpis riadka tabuľky
            echo $this->Html->tableCells($_item['User'], array('class' => 'odd'), array('class' => 'even'));
        }
?>
    </tbody>
</table>
<?php echo $this->element('pagination'); ?>