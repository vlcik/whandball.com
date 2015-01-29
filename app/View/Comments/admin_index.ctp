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
		echo $this->Html->link(__("Article managment", true), array('controller' => 'articles', 'action' => 'index')) . "&nbsp;>&nbsp;" . __('Discussion management', true) . " - " . $article['Article']['title'];
		?>
		<br /> <br />
	</p>
</div>
<h2>
	<?php 
	echo __('Discussion management', true) . " - " . $article['Article']['title'];
	?>
</h2>
<?php 
echo $this->Session->flash();
?>
<table class="info-table">

	<tr>
		<td class="label"><b> <?php 
		echo __('Total comments', true);
		?> :
		</b>
		</td>
		<td><?php 
		echo $this->Paginator->request->params['paging']['Comment']['count'];
		?>
		</td>
	</tr>

	<tr>
		<td class="label"><b> <?php 
		echo __('Banned comments', true);
		?> :
		</b>
		</td>
		<td><?php 
		echo $inactive_comments;
		?>
		</td>
	</tr>
</table>
<div id="comments">
	<table cellspacing="0">
		<thead>
			<tr>
				<th class="first"><?php echo $this->Paginator->sort('Comment.id', '#') ?>
				</th>
				<th><?php echo $this->Paginator->sort('Comment.name', __('User', true)) ?>
				</th>
				<th><?php echo $this->Paginator->sort('Comment.status_id', __('Status', true)) ?>
				</th>
				<th><?php echo  __('IP address', true) ?></th>
				<th><?php echo $this->Paginator->sort('Comment.created', __('Created', true)) ?>
				</th>
				<th><?php echo $this->Paginator->sort('Comment.modified', __('Modified', true)) ?>
				</th>
				<th colspan="2" class="last"><?php __('Action') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php

			foreach($comments as $_item){

				$color = "red";
				$status = $_item['Comment']['status_id'];
				if ($_item['Comment']['status_id'] == ITEM_ACTIVE){
					$color = "green";
				}
				$_item['Comment']['status_id'] = '<span style="color:' . $color . '">' . strtr($_item['Comment']['status_id'], $_statuses) . '</span>';

				$id = $_item['Comment']['id'];
				$_item['Comment']['id'] = $this->Js->link(
						$id, 
						array(
								'controller' => 'comments',
								'action' => 'view',
								$_item['Comment']['id']
						),
						array(
								'escape' => false,
								'update' => "#comment-content",
								'title' => __('Upraviť', true),
								'class' => 'title-link',
								'id' => 'title-link-' . $_item['Comment']['id']
						)
				);

				$_item['Comment']['created'] = $this->Time->format('j/m/Y, H:i:s', $_item['Comment']['created'], null);
				$_item['Comment']['modified'] = $this->Time->format('j/m/Y, H:i:s', $_item['Comment']['modified'], null);

				$status_icon = "";
				$status_comment_url = array(
						'controller' => 'comments',
						'action' => 'change_comment_status',
						'admin' => true,
						$id
				);
				if ($status == ITEM_ACTIVE){
					$status_icon = $this->Html->link(
							$this->Html->image(
									'comment_wrong.png',
									array(
										'alt' => __('Inactivate', true)
								)
							),
							$status_comment_url,
							array(
									'title' => __('Inactivate', true),
									'escape' => false,
							),
							__("Do you really want to inactivate comment with ID: ", true) . $id . " ?"
					);
				}
				else {
					$status_icon = $this->Html->link(
						$this->Html->image(
								'comment_ok.png',
								array(
										'alt' => __('Activate', true)
								)
						),
						$status_comment_url,
						array(
								'title' => __('Activate', true),
								'escape' => false,
						),
						__("Do you really want to activate comment: ", true) . $id . " ?"
					);
				}

				$ban_ip_icon = "";
				if (!in_array($_item['Comment']['ip'], $banned_ips)){
						$ban_ip_icon = $this->Html->link(
							$this->Html->image(
									'warning.png',
									array(
											'alt' => __('Ban IP adress', true)
									)
							),
							array(
									'controller' => 'comments',
									'action' => 'ban_ip_address',
									'admin' => true,
									$article['Article']['id'],
									$_item['Comment']['ip']
							),
							array(
									'title' => __('Ban IP address', true),
									'escape' => false,
							),
							__("Do you really want to ban this IP adress: ", true) . " " . $_item['Comment']['ip'] . " ?"
					);
				}

				$_item['Comment']['action1'] = $ban_ip_icon;
				$_item['Comment']['action2'] = $status_icon;

				echo $this->Html->tableCells($_item['Comment'], array('class' => 'odd'), array('class' => 'even'));
			}
			?>
		</tbody>
	</table>
	<?php 
	echo $this->element('pagination');

	echo $this->Html->script('simple-modal/basic.js');
	echo $this->Html->script('simple-modal/jquery.simplemodal.js');
	echo $this->Html->css('simple-modal/basic.css');
	?>
	<!-- IE6 "fix" for the close png image -->
	<!--[if lt IE 7]>
	<?php 
	echo $this->Html->css('simple-modal/basic_ie.css');
	?>
	<![endif]-->
	
	<?php 
	echo $this->Html->scriptBlock('
		$(".title-link").click(function() {
		    $("#comment-content").modal({
				overlayClose:true
			});
		});
	');

	echo $this->Js->writeBuffer();
	?>
	<div style='display: none'>
		<?php 
		echo $this->Html->image('x.png', array(
			'alt' => 'Close'
		));
		?>
	</div>
	<div id="comment-content"></div>
</div>
