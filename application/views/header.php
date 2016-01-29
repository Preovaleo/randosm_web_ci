<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>
            <?php echo $titre ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="description" content="Rand'OSM creation de randonnée pour les grands marcheurs" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url() ?>assets/css/style.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/carousel.css" />
<!--        <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url() ?>assets/css/normalize.css" /> -->
        <link rel="icon" href="<?= base_url() ?>assets/images/favicon.ico" />
        <!-- Pour accéder à base_url() depuis le javascript -->
        <script><?php echo 'var base_url="' . base_url() . '";' ?></script>

        <!-- Pour le popup login -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login-popup/css/component.css" />
        <script src="<?= base_url() ?>assets/login-popup/js/modernizr.custom.js"></script>
        <!-- for the blur effect -->

        <script> /* this is important for IEs */ var polyfilter_scriptpath = '/js/';</script>
<!--        <script src="<?= base_url() ?>assets/login-popup/js/cssParser.js"></script>-->
<!--        <script src="<?= base_url() ?>assets/login-popup/js/css-filters-polyfill.js"></script>-->

    </head>
    <body>
        <?php if (isset($connected) && $connected) { ?>
            <header>
                <!-- ################################################ Header Connecté ################################################ -->
                <div id="header-wraper">
                    <a href="<?= base_url() ?>">
                        <img src="<?= base_url() ?>assets/images/Rand_OSM_Logo.svg" alt="Logo Rand'OSM">
                    </a>
                    <div id="connected-box">
                        <span id="username"><?php echo $this->session->userdata('username'); ?></span>
                        <div class="icon-caret-down"></div>
                        <div id="account">
                            <a class="" href="<?= base_url() ?>logout/">Déconnexion</a><br />
                            <a class="" href="<?= base_url() ?>admin/">Mon compte</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="wraper">
            <?php } else {
                ?>
                <header>
                    <!-- Header Déconnecté -->
                    <div id="header-wraper">
                        <a href="<?= base_url() ?>">
                            <img src="<?= base_url() ?>assets/images/Rand_OSM_Logo.svg" alt="Logo Rand'OSM">
                        </a>
                        <div id="login-subscribe">
                            <div class="container">
                                <button class="md-trigger button" data-modal="modal-16">Se connecter</button>
                            </div>
                            <a class="button" href="<?= base_url() ?>signup/">S'inscrire</a>
                        </div>
                    </div>
                </header>
                <div class="wraper">


                    <!-- Login form -->
                    <div class="md-modal md-effect-16 connect-box" id="modal-16">
                        <div class="md-content">
                            <h3>Connexion</h3>
                            <div>
    <?php
    echo form_open('admin/login', "class='login-form'");

    $attributes2 = array(
        'class' => 'icon-user login-field-icon'
    );

    $attributes4 = array(
        'class' => 'icon-lock login-field-icon'
    );
    $attributes1 = array(
        'name' => 'username',
        'id' => 'username',
        'class' => 'login-field',
        'placeholder' => 'Identifiant',
        'required' => ''
    );
    $attributes3 = array(
        'name' => 'password',
        'id' => 'password',
        'class' => 'login-field',
        'placeholder' => 'Mot de passe',
        'required' => ''
    );


    echo form_input($attributes1);
    echo form_label('', 'username', $attributes2);

    echo form_password($attributes3);
    echo form_label('', 'password', $attributes4);

    echo form_submit('submit', 'Connexion', "class='button'");

    echo "<a href='" . base_url() . "pw_forgotten'>Mot de passe oublié ?</a>";
    echo form_close();
    ?>
                            </div>
                        </div>
                    </div>

                    <div class="md-overlay"></div>
                    <!--##### -->

<?php } ?>

                <!-- classie.js by @desandro: https://github.com/desandro/classie -->
                <script src="<?= base_url() ?>assets/login-popup/js/classie.js"></script>
                <script src="<?= base_url() ?>assets/login-popup/js/modalEffects.js"></script>
