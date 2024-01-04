<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $lang('signup') ;?></title>
        <link rel="stylesheet" href="<?= $assetsPath ;?>/styles.css"  />
    </head>
    <body class="signup">
        <div class="container" style="padding-top:25px; padding-bottom: 25px">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">  
                    <form method="post">
                        <h3 class="text-center"><?= $lang('signup') ;?></h3>

                        <?php if( config_item('csrf_protection') === TRUE) { ?>

                            <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                            ?>

                            <input type="hidden" name="<?= $csrf['name'] ;?>" value="<?= $csrf['hash'] ;?>" />

                        <?php } ?>

                        <?php foreach( $signupFields as $name => $attrs){ ?>

                            <?php
                                if(isset($attrs['checkbox']) || isset($attrs['radio']) || isset($attrs['select']))
                                {
                                    $type   = isset($attrs['checkbox']) ? 'checkbox' :  (isset($attrs['radio']) ? 'radio' : 'select');
                                    $values = $attrs[$type];
                                    unset($attrs[$type]);
                                    list($label, $htmlAttrs) = $attrs;
                                }
                                else
                                {
                                    list($type, $label, $htmlAttrs) = $attrs;
                                }
                            ?>

                            <div class="form-group <?= isset($validationErrors[$name]) ? 'has-error has-feedback' : '' ;?>">
                                <label><?= $label ;?></label>
                                <?php if(!in_array($type, ['checkbox', 'radio', 'select'])){ ?>

                                    <input name="<?= $name ;?>" type="<?= $type ;?>"
                                    <?php
                                        if(!isset($htmlAttrs['class']))
                                        {
                                            echo 'class="form-control"';
                                        }

                                        foreach($htmlAttrs as $_name => $_value)
                                        {
                                            if($_name == 'value')
                                            {
                                                continue;
                                            }
                                            echo $_name . (!empty($_value) ? '="' . ( !is_bool($_value) ? $_value : ($_value === true ? 'true' : 'false') ) .'"' : '');
                                        }

                                        if($type != 'password')
                                        {
                                            echo 'value="' . set_value($name) . '"';
                                        }
                                    ?>
                                    />

                                <?php } else { ?>

                                    <?php if($type != 'select') { ?>

                                        <?php foreach($values as $_value => $_label){ ?>
                                            <label style="display:block; font-weight: normal">
                                                <input type="<?= $type ;?>" name="<?= $name ;?>" value="<?= $_value;?> "/> <?= $_label ;?>
                                            </label>
                                        <?php } ?>

                                    <?php } else { ?>

                                        <select name="<?= $name ;?>"
                                        <?php
                                            if(!isset($htmlAttrs['class']))
                                            {
                                                echo 'class="form-control"';
                                            }

                                            foreach($htmlAttrs as $_name => $_value)
                                            {
                                                if($_name == 'value')
                                                {
                                                    continue;
                                                }
                                                echo $_name . (!empty($_value) ? '="' . ( $_name == 'required' ? 'required' : ( !is_bool($_value) ? $_value : ($_value === true ? 'true' : 'false') ) ) .'"' : '');
                                            }
                                        ?>
                                        >
                                            <?php foreach($values as $_value => $_label){ ?>
                                                <option value="<?= $_value ;?>" <?= set_value($name) == $_value ? 'selected="selected"' : '' ;?>><?= $_label ; ?></option>
                                            <?php } ?>
                                        </select>

                                    <?php } ?>

                                <?php } ?>

                                <?php if(isset($validationErrors[$name])) { ?>
                                    <div class="help-block"><?= $validationErrors[$name] ;?></div>
                                <?php } ;?>
                            </div>

                        <?php } ?>

                        <button type="submit" class="btn btn-primary btn-block"><?= $lang('signup_btn') ;?></button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>