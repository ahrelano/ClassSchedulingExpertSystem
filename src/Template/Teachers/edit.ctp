<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $teacher->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $teacher->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Teachers'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="teachers form large-9 medium-8 columns content">
    <?= $this->Form->create($teacher) ?>
    <fieldset>
        <legend><?= __('Edit Teacher') ?></legend>
        <?php
            echo $this->Form->control('lastname', ['class'=>'form-control']);
            echo $this->Form->control('firstname', ['class'=>'form-control']);
            echo $this->Form->control('middle', ['class'=>'form-control']);
            echo $this->Form->control('position_id', ['options' => $positions, 'class'=>'form-control']);
            echo $this->Form->control('subject_id', ['options' => $subjects, 'class'=>'form-control', 'empty' => true, 'label'=>'Major']);
            echo $this->Form->control('loads', ['class'=>'form-control']);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit', ['class'=>'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
