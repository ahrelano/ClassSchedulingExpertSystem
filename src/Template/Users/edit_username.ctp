<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit Password') ?></legend>
        <?php
            echo $this->Form->control('username', ['class'=>'form-control', 'required'=>true]);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit', ['class'=>'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>