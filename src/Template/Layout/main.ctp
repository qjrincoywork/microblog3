<!DOCTYPE htm>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta name="csrf-token" content="<?=$this->request->getParam('_csrfToken')?>">
        <link rel="icon" href="/img/microMinilogo.png">
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
            echo $this->Html->css(['font-face','all.min','fontawesome.min',
                                   'material-design-iconic-font.min', 'fonts',
                                   'bootstrap.min', 'animsition.min', 'perfect-scrollbar',
                                   'simplebar', 'app', 'theme', 'style'
            ]);
        ?>
    </head>
<body>
    <?= $this->element('sidebar'); ?>
    
    <div class="page-container" id='pageContainer'>
        <?php echo $this->element('header'); ?>
        <div class='modal fade' tabindex='-1' role='dialog'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='myModalLabel'></h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body pt-2'>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main-content" style=" height: 100%;">
            <div id="mainContent" class="section__content section__content--p30">
                <?= $this->Flash->render() ?>
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <div class="body-class animsition">
        <div id="footer">
            <?php echo $this->Html->script(['jquery-3.1.1.min','popper.min','bootstrap.min',
                                            'bootstrap-notify.min', 'animsition.min', 'perfect-scrollbar',
                                            'simplebar', 'main', 'app-layout', 'user-scripts'
            ]);
            ?>
        </div>
    </div>
</body>
</html>
