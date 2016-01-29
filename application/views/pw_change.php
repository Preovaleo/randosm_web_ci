<!--Cette view est utilisée lorsque l'utilisateur est connecté, et que celui-ci souhaite modifier son mot de passe-->

<?php $page_en_cours='MonCompte' ; ?>
<div class='content'>
    <div class="subscribe-box content-box">
        <h1>Modifier mon mot de passe</h1>
        <?php if (!isset($success)) { ?>

        <?=form_open( 'admin/pw_change'); ?>

            <label for="pass">Mot de passe actuel :</label>
            <input type="password" name="pass" id="pass" value="" />
            <br />
            <label for="pass1">Nouveau mot de passe :</label>
            <input type="password" name="pass1" id="pass1" value="" />
            <br />
            <label for="pass2">Vérification du mot de passe :</label>
            <input type="password" name="pass2" id="pass2" value="" />
            <br />
            <input class="button" type="submit" value="Enregistrer" />
            <a class="button" href="<?= base_url() ?>admin/">Retour</a>

            <?=form_close();?>

                <div class="errors">
                    <p>
                        <?=form_error( 'pass', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'pass1', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'pass2', '<span class="error">', '</span');?>
                    </p>
                </div>

                <?php } else {?>
                <div class="success">
                    <br/>
                    <?php echo $success;?>
                </div>
                <br />
                <a href="<?= base_url() ?>/admin">Retour au site</a>
                <?php } ?>
    </div>
</div>
