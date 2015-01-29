<div class="admin-menu">
    <p>

            <?php
            	echo $this->Html->image('profile.png', array('alt' => 'My profile'));
                echo $this->Html->link(__('My profile', true), array('controller' => 'users', 'action' => 'profile', 'admin' => true));
            ?>
        |
            <?php
            	echo $this->Html->image('article.png', array('alt' => 'Articles management'));
                echo $this->Html->link(__('Articles', true), array('controller' => 'articles', 'action' => 'index', 'admin' => true));
            ?>
        |
            <?php
            	echo $this->Html->image('category.png', array('alt' => 'Categories management'));
                echo $this->Html->link(__('Categories', true), array('controller' => 'categories', 'action' => 'index', 'admin' => true));
            ?>
        |
            <?php
            	echo $this->Html->image('people.png', array('alt' => 'Users management'));
                echo $this->Html->link(__('Users', true), array('controller' => 'users', 'action' => 'index', 'admin' => true));
            ?>
        |   
            <?php
            	echo $this->Html->image('out.png', array('alt' => 'Logout'));
                echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'admin' => false));
            ?>

    </p>
</div>