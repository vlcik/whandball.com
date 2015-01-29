<div class="paginator" style="font-size: 13px">  
    <?php

        if($this->Paginator->hasPrev())
            echo $this->Paginator->prev(
                __('< Predchádzajúca', true),
                null,
                __('< Predchádzajúca', true),
                array('class' => 'prev')
            );
    ?>
        <span class="pages">
            <?php
                echo $this->Paginator->numbers(array(
                    'before' => "&nbsp;",
                    'after' => "&nbsp;",
                ));
            ?>
        </span>
    <?php
        if($this->Paginator->hasNext())
            echo $this->Paginator->next(__('Nasledujúca >', true), null, __('Nasledujúca >', true), array('class' => 'next'));
    ?>
</div>