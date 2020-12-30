<?= $this->Html->css('login.css') ?>

<div id="fullscreen_bg" class="fullscreen_bg"/>

<div class="container">
<?= $this->Form->create('post', ['class'=>'form-signin']) ?>
	<?= $this->Flash->render() ?>
	<h1 class="form-signin-heading text-muted">Sign In</h1>
	<?= $this->Form->control('username', ['class'=>'form-control', 'placeholder'=>'Username', 'required'=>true, 'autofocus'=>true]) ?>
	<?= $this->Form->control('password', ['class'=>'form-control', 'placeholder'=>'Password', 'required'=>true]) ?>
	<?= $this->Form->button('Sign In', ['class'=>'btn btn-lg btn-primary btn-block']) ?>
	<?= $this->Form->end() ?>
</div>