<div class="categories">
	<?php 

	$_statuses = array(
			ITEM_INACTIVE => __('Inactive', true),
			ITEM_ACTIVE => __('Active', true),
			ITEM_DELETED => __('Deleted', true),
	);
	?>
	<h2>
		<?php
		echo $category['Category']['name'] . " - " . __('Categories management', true);
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
						'alt' => __('Add subcategory', true)
				)
		);
		echo $this->Html->link(
				__("Add subcategory", true),
				array(
                    'controller' => 'categories',
                    'action' => 'add',
					$category['Category']['id']
                ),
                array(
                    'escape' => false,
                    'title' => __('Add subcategory', true)
                )
            );
			?>
	</div>

	<?php 
	$p = "";
	if (!is_null($path) && ($category['Category']['id'] != ROOT_CATEGORY)){
		$i = 1;
		foreach($path as $_item){
			if ($category['Category']['id'] != $_item['Category']['id']){
					$p .= $this->Html->link(
						$_item['Category']['name'],
						array(
								'controller' => 'categories',
								'action' => 'index',
								'admin' => true,
								$_item['Category']['id']
						),
						array(
								'escape' => false,
								'title' => __('Show subcategory', true)
						)
					);
				}
				else {
					$p .= $_item['Category']['name'];
			 	}

			 	if ($i++ != count($path)){
					$p .= $this->Html->image(
	                    'arrow-to-right.png',
	                    array(
	                    	'width' => 15,
	                    	'height' => 15,
	                    	'class' => 'arrow',
	                        'alt' => __('arrow to right', true)
	                    )
	                );
				}
		}
	}
	else {
			$p .= __('Root', true);
		}
		?>
	<table class="info-table">

		<tr>
			<td class="label"><b> <?php 
			echo __('ID', true);
			?> :
			</b></td>
			<td class="category-path"><?php 
			echo $category['Category']['id'];
			?></td>
		</tr>
		<tr>
			<td class="label"><b> <?php 
			echo __('Path', true);
			?> :
			</b></td>
			<td class="category-path"><?php 
			echo $p;
			?></td>
		</tr>

		<tr>
			<td class="label"><b> <?php 
			echo __('Status', true);
			?> :
			</b></td>
			<td class="category-path"><?php
			$color = "red";
			if ($category['Category']['status_id'] == ITEM_ACTIVE){
		               $color = "green";
		            }
		            echo '<span style="color:' . $color . '">' . strtr($category['Category']['status_id'], $_statuses) . '</span>';
		            ?></td>
		</tr>

		<tr>
			<td class="label"><b> <?php 
			echo __('Created', true);
			?> :
			</b></td>
			<td class="category-path"><?php 
			echo $this->Time->format('j/m/Y, H:i:s', $category['Category']['created'], null);
			?></td>
		</tr>

		<tr>
			<td class="label"><b> <?php 
			echo __('Last modified', true);
			?> :
			</b></td>
			<td class="category-path"><?php 
			echo $this->Time->format('j/m/Y, H:i:s', $category['Category']['modified'], null);
			?></td>
		</tr>
	</table>

	<div class="subcategories">
		<table cellspacing="0">
			<thead>
				<tr>
					<th class="first"><?php echo $this->Paginator->sort('Category.id', 'ID') ?>
					</th>
					<th><?php echo $this->Paginator->sort('Category.name', __('Name', true)) ?>
					</th>
					<th><?php echo $this->Paginator->sort('Category.status_id', __('Status', true)) ?>
					</th>
					<th><?php echo $this->Paginator->sort('Category.created', __('Created', true)) ?>
					</th>
					<th><?php echo $this->Paginator->sort('Category.modified', __('Modified', true)) ?>
					</th>
					<th colspan="2" class="last"><?php echo __('Action') ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php                
				foreach($categories as $_item) {

		        	$color = "red";
		        	if ($_item['Category']['status_id'] == ITEM_ACTIVE){
		               $color = "green";
		            }
		            $_item['Category']['status_id'] = '<span style="color:' . $color . '">' . strtr($_item['Category']['status_id'], $_statuses) . '</span>';


		            $name = $_item['Category']['name'];
		            $_item['Category']['name'] = $this->Html->link(
		      
						$name,
			            array(
			            	'controller' => 'categories',
			            	'action' => 'index',
			            	'admin' => true,
			            	$_item['Category']['id']
			            ),
			            array(
			            	'escape' => false,
			                'title' => __('Show subcategory', true)
			            )
			        );
					$_item['Category']['created'] = $this->Time->format('j/m/Y, H:i:s', $_item['Category']['created'], null);
					$_item['Category']['modified'] = $this->Time->format('j/m/Y, H:i:s', $_item['Category']['modified'], null);
						
					$_item['Category']['action'] = $this->Html->link(
		      
							$this->Html->image(
			                    'edit.png',
			                    array(
			                        'alt' => __('Upraviť', true)
			                    )
			                ),
			                array(
			                    'controller' => 'categories',
			                    'action' => 'edit',
			                    'admin' => true,
			                    $_item['Category']['id']
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
			                    'controller' => 'categories',
			                    'action' => 'delete',
			                    'admin' => true,
			                    $_item['Category']['id']
			                ),
			                array(
			                    'escape' => false,
			                    'title' => __('Odstrániť', true)
			                ),
			                __("Are you sure to delete this category: ", true) . " " . $name . " ?"
			       
			            );

		            // Výpis riadka tabuľky
		            echo $this->Html->tableCells($_item['Category'], array('class' => 'odd'), array('class' => 'even'));
		        }
		        ?>
			</tbody>
		</table>
		<?php echo $this->element('pagination'); ?>
	</div>
</div>
