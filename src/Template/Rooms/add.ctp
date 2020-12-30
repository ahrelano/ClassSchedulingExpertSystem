<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Rooms'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Buildings'), ['controller' => 'Buildings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Building'), ['controller' => 'Buildings', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="rooms form large-9 medium-8 columns content">
    <?= $this->Form->create($room) ?>
    <fieldset>
        <legend><?= __('Add Room') ?></legend>
        <?php
        $grade = [
            '7' => 'Grade 7',
            '8' => 'Grade 8',
            '9' => 'Grade 9',
            '10' => 'Grade 10',
            '11' => 'Grade 11',
            '12' => 'Grade 12',
        ];
            echo $this->Form->control('building_id', ['options' => $buildings, 'class'=>'form-control']);
            echo $this->Form->control('room', ['class'=>'form-control']);
            echo $this->Form->control('grade', ['options'=>$grade, 'class'=>'form-control', 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit',['class'=>'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
