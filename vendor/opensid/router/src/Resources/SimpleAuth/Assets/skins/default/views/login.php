<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $lang('login') ;?></title>
        <link rel="stylesheet" href="<?= $assetsPath ;?>/styles.css"  />
    </head>
    <body class="login">
        <div class="container" style="padding-top:50px; padding-bottom: 50px">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                    <form method="post">
                        <div class="panel panel-default login-box">
                            <div class="panel-body">
                                <h3 class="text-center"><?= $lang('login') ;?></h3>

                                <?php foreach( $messages as $type => $message){ ?>
                                    <div class="alert alert-<?= $type ;?>"><?= $lang($message) ;?></div>
                                <?php } ?>

                                <?php if( config_item('csrf_protection') === TRUE) { ?>

                                    <?php
                                        $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                        );
                                    ?>

                                    <input type="hidden" name="<?= $csrf['name'] ;?>" value="<?= $csrf['hash'] ;?>" />

                                <?php } ?>

                                <div class="form-group">
                                    <input type="email" name="<?= config_item('auth_form_username_field'); ?>" placeholder="<?= ucwords(config_item('auth_form_username_field')); ?>" class="form-control" required />
                                </div>

                                <div class="form-group">
                                    <input type="password" name="<?= config_item('auth_form_password_field'); ?>" placeholder="<?= ucwords(config_item('auth_form_password_field')); ?>" class="form-control" required />
                                </div>

                                <?php if( config_item('simpleauth_enable_remember_me') == true){ ?>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="<?= config_item('simpleauth_remember_me_field');?>" /> <?= $lang('remember_me') ;?>
                                    </label>
                                </div>
                                <?php } ?>

                                <button type="submit" class="btn btn-primary btn-block"><?= $lang('enter') ;?></button>

                                <?php if( config_item('simpleauth_enable_password_reset') === TRUE || config_item('simpleauth_enable_signup') === TRUE){ ?>
                                    <hr />
                                <?php } ?>


                                <?php if(config_item('simpleauth_enable_password_reset') === TRUE){ ?>
                                    <div class="form-group text-center">
                                        <a href="<?= route('password_reset') ;?>">
                                            <?= $lang('forgotten_password_link') ;?>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if(config_item('simpleauth_enable_signup') === TRUE){ ?>
                                    <div class="form-group text-center">
                                        <a href="<?= route('signup') ;?>">
                                            <?= $lang('register_link') ;?>
                                        </a>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>