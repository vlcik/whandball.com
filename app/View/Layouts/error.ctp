
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta http-equiv="Expire" content="now" />
        <meta http-equiv="Pragma" content="no-cache" />
        <?php 
        	echo $this->Html->charset();
        ?>
        <meta http-equiv="Content-Style-Type" content="text/css" />

        <link rel="shortcut icon" href="<?php echo $this->base; ?>/favicon.ico" type="image/x-icon" />
        <title><?php echo $title_for_layout;?> :: <?php echo __('Editor mode', true); ?></title>
        <?php

            echo $this->Html->css("style_backend");
            //echo $this->Html->css("cake.generic");

            echo $this->Html->script("jquery-1.9.1.js");

        ?>

    </head>
    <body id="page2">

        <div class="tail-top">
            <!-- header -->
            <div class="admin-header">

                <h1>
                    whandball.com                </h1>
            </div>
            <!-- content -->
            <div id="content">

                <div class="tail-middle">
                    <div class="box">
                        <div class="inner">
                        	<?php echo $this->Session->flash(); ?>
                            <?php echo $content_for_layout; ?>
                        </div>
                    </div>

                </div>
                <div class="tail-bottom">

                </div>
            </div>
            
        </div>
    </body>
</html>
