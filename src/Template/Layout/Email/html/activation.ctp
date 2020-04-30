<!doctype html>
<html>
    <head>
        <?= $this->Html->charset()?>
        <style>
            @media all {
                body {
                    min-width: 992px !important;
                    font-family: cursive;
                }

                body .page-container {
                    padding: 2rem;
                }

                .container {
                    min-width: 992px !important;
                    padding: 2rem;
                }
                .border {
                    border: 1px solid #dee2e6 !important;
                }
                .card {
                    position: relative;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    min-width: 0;
                    word-wrap: break-word;
                    background-color: #fff;
                    background-clip: border-box;
                    border: 1px solid rgba(0, 0, 0, 0.125);
                    border-radius: 0.25rem;
                    padding: 2rem;
                }
                
                .card-body {
                    -webkit-box-flex: 1;
                    -ms-flex: 1 1 auto;
                    flex: 1 1 auto;
                    padding: 1.25rem;
                    background-color: #e8e8e8;
                }

                dt {
                    font-weight: 700;
                }

                dd {
                    margin-bottom: .5rem;
                    margin-left: 0;
                }

                .btn-outline-primary {
                    color: #3490dc;
                    border-color: #3490dc;
                }

                .btn-outline-primary:hover {
                    color: #fff;
                    background-color: #3490dc;
                    border-color: #3490dc;
                }

                .btn-outline-primary:focus,
                .btn-outline-primary.focus {
                    -webkit-box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                }

                .btn-outline-primary.disabled,
                .btn-outline-primary:disabled {
                    color: #3490dc;
                    background-color: transparent;
                }

                .btn-outline-primary:not(:disabled):not(.disabled):active,
                .btn-outline-primary:not(:disabled):not(.disabled).active,
                .show>.btn-outline-primary.dropdown-toggle {
                    color: #fff;
                    background-color: #3490dc;
                    border-color: #3490dc;
                }

                .btn-outline-primary:not(:disabled):not(.disabled):active:focus,
                .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
                .show>.btn-outline-primary.dropdown-toggle:focus {
                    -webkit-box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                }
                a {
                    color: #3490dc;
                    text-decoration: none;
                    background-color: transparent;
                }

                a:hover {
                    color: #1d68a7;
                    -webkit-box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.5);
                    text-decoration: none;
                    border-radius: .5rem;
                    padding: .5em
                }

                a:not([href]):not([tabindex]) {
                    color: inherit;
                    text-decoration: none;
                }

                a:not([href]):not([tabindex]):hover,
                a:not([href]):not([tabindex]):focus {
                    color: inherit;
                    text-decoration: none;
                }

                a:not([href]):not([tabindex]):focus {
                    outline: 0;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-container">
            <div class="container border">
                Dear <span style='color:#666666'><strong><?=ucwords($name) ?></span></strong>,<br/><br/>
                <p>Your account has been created successfully.<br/>
                    Please look at the details of your account below: </p>
                <div class="card">
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
                <p><small>This is an auto generated message. please do not reply.</small></p>
            </div>
        </div>
    </body>
</html>