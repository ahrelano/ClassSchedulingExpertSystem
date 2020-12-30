<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Buildings'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="buildings form large-9 medium-8 columns content">
    <?= $this->Form->create($building) ?>
    <fieldset>
        <legend><?= __('Add Building') ?></legend>
        <?php
            echo $this->Form->control('number', ['class'=>'form-control']);
            echo $this->Form->control('building', ['class'=>'form-control']);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit', ['class'=>'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
