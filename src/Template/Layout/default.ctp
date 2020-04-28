<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
        <?= $this->Html->charset() ?>
        <meta name="csrf-token" content="<?=$this->request->getParam('_csrfToken')?>">
        <link rel="icon" href="/img/microMinilogo.png">
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <?= $this->Html->css(['font-face', 'all.min', 'fontawesome.min', 
                                   'material-design-iconic-font.min','fonts', 'bootstrap.min', 'app'
            ]);?>
    </head>
    <body>
        <div class="page-container">
            <header class="header-desktop">
                <div class="col-md-1 offset-md-11">
                    <?php if($this->request->getAttribute("here") == '/users/login'):?>
                    <?=
                        $this->Html->link(
                            'Sign up',
                            ['controller' => 'Users', 'action' => 'register', '_full' => true],
                            ['class' => 'auth-btn btn-sm btn-outline-secondary', 'escape' => false]
                        );
                    ?>
                    <?php else:?>
                    <?=
                        $this->Html->link(
                            'Login',
                            ['controller' => 'Users', 'action' => 'login', '_full' => true],
                            ['class' => 'auth-btn btn-sm btn-outline-secondary', 'escape' => false]
                        );
                    ?>
                    <?php endif;?>
                </div>
            </header>
            <div id="content">
                <?= $this->Flash->render('auth'); ?>
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content'); ?>
            </div>
            <div id="footer">
                <?= $this->Html->script(['jquery-3.2.1.min', 'jquery-ui.min',
                                         'bootstrap-notify.min','app-layout', 'auth-scripts'
                ]); ?>
            </div>
        </div>
    </body>
</html>
