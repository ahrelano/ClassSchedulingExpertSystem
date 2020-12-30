<?php foreach ($users as $user) { ?>
<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Username'), ['action' => 'edit-username', $user->id]) ?> </li>
        <li><?= $this->Html->link(__('Edit Password'), ['action' => 'edit', $user->id]) ?> </li>
    </ul>
</nav>
<?php } ?>