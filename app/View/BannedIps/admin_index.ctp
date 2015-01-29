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
		echo $this->Html->link(__("Article managment", true), array('controller' => 'articles', 'action' => 'index')) . "&nbsp;>&nbsp;" . __('Banned IP addresses management', true);
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

?>
<div class="page-offer">
	<?php
		echo $this->Html->image(
            'add.png',
            array(
                'alt' => __('Add banned IP address', true)
            )
        );
		echo $this->Html->link(
                __('Add banned IP address', true), 
                array(
                    'controller' => 'bannedIps',
                    'action' => 'add'
                ),
                array(
                    'escape' => false,
                    'title' => __('Add banned IP address', true)
                )
            ) 
	?>
</div>
<div>
	<table cellspacing="0">
		<thead>
			<tr>
				<th class="first"><?php echo $this->Paginator->sort('BannedIp.id', '#') ?>
				</th>
				<th><?php echo $this->Paginator->sort('BannedIp.ip', __('IP address', true)) ?>
				</th>
				<th><?php echo $this->Paginator->sort('BannedIp.status_id', __('Status', true)) ?>
				</th>
				<th><?php echo $this->Paginator->sort('BannedIp.created', __('Created', true)) ?>
				</th>
				<th><?php echo $this->Paginator->sort('BannedIp.modified', __('Modified', true)) ?>
				</th>
				<th colspan="2" class="last"><?php echo __('Action') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php

			foreach($bannedIps as $_item){

				$color = "red";
				$status = $_item['BannedIp']['status_id'];
				if ($_item['BannedIp']['status_id'] == ITEM_ACTIVE){
					$color = "green";
				}
				$_item['BannedIp']['status_id'] = '<span style="color:' . $color . '">' . strtr($_item['BannedIp']['status_id'], $_statuses) . '</span>';


				$_item['BannedIp']['created'] = $this->Time->format('j/m/Y, H:i:s', $_item['BannedIp']['created'], null);
				$_item['BannedIp']['modified'] = $this->Time->format('j/m/Y, H:i:s', $_item['BannedIp']['modified'], null);

				$_item['BannedIp']['action1'] = $this->Html->link(
						$this->Html->image(
								'delete.png',
								array(
										'alt' => __('Delete banned IP adress', true)
								)
						),
						array(
								'controller' => 'bannedIps',
								'action' => 'delete',
								'admin' => true,
								$_item['BannedIp']['id']
						),
						array(
								'title' => __('Delete banned IP address', true),
								'escape' => false,
						),
						__("Do you really want to delete this IP adress: ", true) . " " . $_item['BannedIp']['ip'] . " ?"
				);

				echo $this->Html->tableCells($_item['BannedIp'], array('class' => 'odd'), array('class' => 'even'));
			}
			?>
		</tbody>
	</table>
	<?php 
	echo $this->element('pagination');
	echo $this->Js->writeBuffer();
	?>
</div>
