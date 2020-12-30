<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    
    <?= $this->Html->css('../vendor/bootstrap/css/bootstrap.min.css') ?>
    <?= $this->Html->script('../vendor/jquery/jquery.min.js') ?>
    <?= $this->Html->script('../vendor/bootstrap/js/bootstrap.min.js') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href=""><?= $this->fetch('title') ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <?php if ($this->request->session()->check('Auth.User')) { ?>
                    <li><?= $this->Html->link(__('Home'), ['controller'=>'Home', 'action'=>'index']) ?></li>
                    <li><?= $this->Html->link(__('Schedule'), ['controller'=>'Home', 'action'=>'schedule']) ?></li>
                    <li><?= $this->Html->link(__('Rooms'), ['controller'=>'Rooms', 'action'=>'index']) ?></li>
                    <li><?= $this->Html->link(__('Buildings'), ['controller'=>'Buildings', 'action'=>'index']) ?></li>
                    <li><?= $this->Html->link(__('Teachers'), ['controller'=>'Teachers', 'action'=>'index']) ?></li>
                    <li><?= $this->Html->link(__('Profile'), ['controller'=>'Users', 'action'=>'index']) ?></li>
                    <li><?= $this->Html->link(__('Logout'), ['controller'=>'Home', 'action'=>'logout']) ?></li>
                <?php }else{ ?>
                    <li><?= $this->Html->link(__('Schedule'), ['controller'=>'Home', 'action'=>'schedule']) ?></li>
                    <li><?= $this->Html->link(__('Admin'), ['controller'=>'Home', 'action'=>'login']) ?></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
