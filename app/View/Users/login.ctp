<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Zadajte prihlasovacie meno a heslo'); ?></legend>
        <?php 

$this->set('title_for_layout', '| Prihlásenie do administrácie');

echo $this->Form->input('username', array(
	'label' => __('Prihlasovacie meno', true)
	));

        echo $this->Form->input('password', array(
	'label' => __('Heslo', true)
	));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Prihlásiť')); ?>
</div>