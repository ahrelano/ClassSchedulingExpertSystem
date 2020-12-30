<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Becuran High School
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('../vendor/bootstrap/css/bootstrap.min.css') ?>
    <?= $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') ?>
    <!-- Custom styles for this template -->
    <?= $this->Html->css('owl.carousel.css') ?>
    <?= $this->Html->css('owl.theme.default.min.css') ?>
    <?= $this->Html->css('animate.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body id="page-top">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <?= $this->Html->link(__($this->Html->Image('logo.png')), ['controller'=>'Home', 'action'=>'login'], ['class'=>'navbar-brand page-scroll', 'escape' => false, 'target'=>'_blank']) ?>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                    <?php if ($this->request->session()->check('Auth.User')) { ?>
                        <li><a class="page-scroll" href="#page-top">Home</a></li>
                        <li><a class="page-scroll" href="#mission-vision">Misson & Vision</a></li>
                        <li><a class="page-scroll" href="#map">Map</a></li>
                        <li><a class="page-scroll" href="#schedule">Schedule</a></li>
                        <li><a class="page-scroll" href="#faculty">Faculty</a></li>
                        <li><a class="page-scroll" href="#master-lists">Master Lists</a></li>
                        <li><?= $this->Html->link(__('Panel'), ['controller'=>'Teachers', 'action'=>'index']) ?></li>
                        <li><?= $this->Html->link(__('Logout'), ['controller'=>'Home', 'action'=>'logout']) ?></li>
                    <?php }else{ ?>
                        <li><a class="page-scroll" href="#page-top">Home</a></li>
                        <li><a class="page-scroll" href="#mission-vision">Misson & Vision</a></li>
                        <li><a class="page-scroll" href="#map">Map</a></li>
                        <li><a class="page-scroll" href="#schedule">Schedule</a></li>
                        <li><a class="page-scroll" href="#faculty">Faculty</a></li>
                        <li><a class="page-scroll" href="#master-lists">Master Lists</a></li>
                    <?php } ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    <!-- Header -->
    <header>
        <div class="container-fluid">
            <div class="slider-container">
                <div class="item">
                    <div class="owl-slider-item">
                        <?= $this->Html->Image('header.png', ['class'=>'img-responsive']) ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <?= $this->Html->script('../vendor/jquery-easing/jquery.easing.min.js') ?>
    <?= $this->Html->script('../vendor/bootstrap/js/bootstrap.min.js') ?>
    <?= $this->Html->script('owl.carousel.min.js') ?>
    <?= $this->Html->script('cbpAnimatedHeader.js') ?>
    <?= $this->Html->script('jquery.appear.js') ?>
    <?= $this->Html->script('SmoothScroll.min.js') ?>
    <?= $this->Html->script('theme-scripts.js') ?>
</body>
</html>
