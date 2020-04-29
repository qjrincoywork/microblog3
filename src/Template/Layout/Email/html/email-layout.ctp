
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset()?>
        <?= $this->Html->css(['app']);?>
    </head>
    <body>
        <div class="page-container">
            <div class="container border mt-2 p-5">
                <div class='email-image' >
                    <img src="/img/microbloglogo.png"/>
                </div>
                Dear <span style='color:#666666'><strong><?=ucwords($name) ?></span></strong>,<br/><br/>
                <p>Your account has been created successfully.<br/>
                    Please look at the details of your account below: </p>
                <div class="card p-5">
                    <div class="card-body">
                        <dl>
                            <dt>Full name</dt>
                                <dd><?=$name?></dd>
                            <dt>Username</dt>
                                <dd><?=$username?></dd>
                            <dt>Email</dt>
                                <dd><?=$email?></dd>
                        </dl>
                        <b>Activate your account by clicking <a class='btn btn-outline-primary' href='<?=$url?>'>Activate Account now</a></b><br/>
                    </div>
                </div>
                <br/>Thanks, <br/>
                <br/>
                <p><small>This is an automatic send-only message.</small></p>
            </div>
        </div>
    </body>
</html>
