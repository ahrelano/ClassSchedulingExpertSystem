<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Building'), ['action' => 'edit', $building->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Building'), ['action' => 'delete', $building->id], ['confirm' => __('Are you sure you want to delete # {0}?', $building->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Buildings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Building'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="buildings view large-9 medium-8 columns content">
    <h3><?= h($building->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Building') ?></th>
            <td><?= h($building->building) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($building->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Number') ?></th>
            <td><?= $this->Number->format($building->number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($building->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($building->modified) ?></td>
        </tr>
    </table>
</div>
