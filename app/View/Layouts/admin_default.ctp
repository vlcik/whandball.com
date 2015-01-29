
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
        <title><?php echo $title_for_layout;?> :: <?php echo __('Administration mode', true); ?></title>
        <?php
        //echo $this->Html->css("bootstrap.min");
            echo $this->Html->css("style_backend");
            //echo $this->Html->css("bootstrap.min");
            //echo $this->Html->css("cake.generic");

            echo $this->Html->script("jquery-1.9.1.js");

        ?>

    </head>
    <body id="page2">

        <div class="tail-top">
            <!-- header -->
            <div class="admin-header">

                <h1>
                    Administration mode
                </h1>

                <div>
                    <?php echo $this->element('admin_menu');?>
                </div>
                
            </div>
            <!-- content -->
            <div id="content">

                <div class="tail-middle">
                    <div class="box">
                        <div class="inner">
                            <?php echo $content_for_layout; ?>
                        </div>
                    </div>

                </div>
                <div class="tail-bottom">

                </div>
            </div>
            <!-- footer -->
            <div id="footer">
                <?php echo $this->element('sql_dump');?>
               
            </div>
        </div>
    </body>
</html>
