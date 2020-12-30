<nav class="large-3 medium-4 columns content text-right" id="actions-sidebar">
    <ul class="side-nav">
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit Password') ?></legend>
        <?php
            echo $this->Form->hidden('id', ['value'=>$user->id]);
            echo $this->Form->control('currentpassword', ['type'=>'password', 'label'=>'Current Password', 'class'=>'form-control','required'=>true, 'value'=>'']);
            echo $this->Form->control('password', ['type'=>'password', 'label'=>'New Password', 'class'=>'form-control','required'=>true, 'value'=>'']);
            echo $this->Form->control('confirmpassword', ['type'=>'password', 'label'=>'Confirm Password', 'class'=>'form-control','required'=>true, 'value'=>'']);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit', ['class'=>'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
