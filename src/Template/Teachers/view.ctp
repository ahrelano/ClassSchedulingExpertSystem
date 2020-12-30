<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Teacher'), ['action' => 'edit', $teacher->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Teacher'), ['action' => 'delete', $teacher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $teacher->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Teachers'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="teachers view large-9 medium-8 columns content">
    <h3><?= h($teacher->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Lastname') ?></th>
            <td><?= h($teacher->lastname) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Firstname') ?></th>
            <td><?= h($teacher->firstname) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Middle') ?></th>
            <td><?= h($teacher->middle) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= $teacher->has('position') ? $teacher->position->position : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Major') ?></th>
            <td><?= $teacher->has('subject') ? $teacher->subject->subject : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($teacher->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Loads') ?></th>
            <td><?= $this->Number->format($teacher->loads) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($teacher->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($teacher->modified) ?></td>
        </tr>
    </table>
</div>
