<div class="admin-menu">
    <p>

            <?php
            	echo $this->Html->image('profile.png', array('alt' => 'My profile'));
                echo $this->Html->link(__('My profile', true), array('controller' => 'users', 'action' => 'profile', 'editor' => true));
            ?>
        |
            <?php
            	echo $this->Html->image('article.png', array('alt' => 'Articles management'));
                echo $this->Html->link(__('Articles', true), array('controller' => 'articles', 'action' => 'index', 'editor' => true));
            ?>
        |   
            <?php
            	echo $this->Html->image('out.png', array('alt' => 'Logout'));
                echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'editor' => false));
            ?>

    </p>
</div>