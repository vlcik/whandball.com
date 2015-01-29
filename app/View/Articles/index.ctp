<h2>
	Article management
</h2>
<?php 
	echo $this->Session->flash();
?>
<div class="page-offer">
	<?php
		echo $this->Html->image(
            'add.png',
            array(
                'alt' => __('Add article', true)
            )
        );
		echo $this->Html->link(
                __("Add article", true),
                array(
                    'controller' => 'articles',
                    'action' => 'add'
                ),
                array(
                    'escape' => false,
                    'title' => __('Add article', true)
                )
            ) 
	?>
&nbsp;&nbsp;
	<?php
		if ($is_admin_mode){
			echo $this->Html->image(
					'monitor.png',
					array(
							'alt' => __('IP addresses management', true)
					)
			);
			
			echo $this->Html->link(
					__('IP addresses management', true),
					array(
							'controller' => 'bannedIps',
							'action' => 'index'
					),
					array(
							'escape' => false,
							'title' => __('IP addresses management', true)
					)
			);

		}
	?>
</div>
<table cellspacing="0" class="table">
    <thead>
        <tr>
            <th class="first"><?php echo $this->Paginator->sort('Article.id', '#') ?></th>
            <th><?php echo $this->Paginator->sort('Article.title', __('Title', true)) ?></th>
            <th><?php echo $this->Paginator->sort('User.username', __('Editor', true)) ?></th>
            <th><?php echo $this->Paginator->sort('Article.article_type_id', __('Article type', true)) ?></th>
            <th><?php echo $this->Paginator->sort('Article.status_id', __('Status', true)) ?></th>
            <th><?php echo $this->Paginator->sort('Article.created', __('Created', true)) ?></th>
            <th><?php echo $this->Paginator->sort('Article.modified', __('Modified', true)) ?></th>
            <th colspan="3" class="last"><?php echo __('Action') ?></th>
        </tr>
    </thead>
    <tbody>
<?php

    $_article_types = array(
        ARTICLE_NON_STATIC => __("Non-static", true),
        ARTICLE_STATIC => __("Static", true),
        ARTICLE_BLOCK => __("Block", true),
    );

    $_article_statuses = array(
        ARTICLE_ACTIVE => __("Active", true),
        ARTICLE_INACTIVE => __("Non-active", true)
    );
    

    foreach($articles as $_item) {
        
    	$color = "red";
		if ($_item['Article']['status_id'] == ITEM_ACTIVE){
        	$color = "green";
        }
        $_item['Article']['status_id'] = '<span style="color:' . $color . '">' . strtr($_item['Article']['status_id'], $_article_statuses) . '</span>';
        

		$title = $_item['Article']['title'];
		$_item['Article']['title'] = $this->Html->link(
			$_item['Article']['title'],
            array(
                'controller' => 'articles',
                'action' => 'view',
                $_item['Article']['id']
            ),
            array(
                'escape' => false,
                'title' => __('Upraviť', true)
            )
        );
        
        if ($is_admin_mode){
        
	        $_item['Article']['user_id'] = $this->Html->link(
	        	$_item['User']['username'],
	            array(
	                'controller' => 'users',
	                'action' => 'edit',
	                'admin' => true,
	                $_item['Article']['user_id']
	            )
	        );
        }
        else {
        	$_item['Article']['user_id'] = $_item['User']['username']; 
        }
        
        $_item['Article']['article_type_id'] = $_article_types[$_item['Article']['article_type_id']]; 
        $_item['Article']['created'] = $this->Time->format('j/m/Y, H:i:s', $_item['Article']['created'], null);
		$_item['Article']['modified'] = $this->Time->format('j/m/Y, H:i:s', $_item['Article']['modified'], null);

		$_item['Article']['action1'] = "";
		if ($is_admin_mode){
			$_item['Article']['action1'] = $this->Html->link(
                $this->Html->image(
                    'comment.png',
                    array(
                        'alt' => __('Management of article comments', true)
                    )
                ),
                array(
                    'controller' => 'comments',
                    'action' => 'index',
                    'admin' => true,
                    $_item['Article']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Management of article comments', true)
                )
            ); 
		}
        $_item['Article']['action2'] = $this->Html->link(
                $this->Html->image(
                    'edit.png',
                    array(
                        'alt' => __('Edit', true)
                    )
                ),
                array(
                    'controller' => 'articles',
                    'action' => 'edit',
                    $_item['Article']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Edit', true)
                )
            );
            $_item['Article']['action3'] = $this->Html->link(
                $this->Html->image(
                    'delete.png',
                    array(
                        'alt' => __('Delete', true)
                    )
                ),
                array(
                    'controller' => 'articles',
                    'action' => 'delete',
                    $_item['Article']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Delete', true)
                ),
                __("Are you sure to delete article: ", true) . " '" . $title . "' ?"
                
            );

        echo $this->Html->tableCells($_item['Article'], array('class' => 'odd'), array('class' => 'even'));
    }
?>
    </tbody>
</table>
<?php echo $this->element('pagination'); ?>