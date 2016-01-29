<?php $page_en_cours='MonCompte' ; ?>
<div class='content'>
    <div class='subscribe-box content-box'>
        <h1>Mon compte</h1>
        <br />
        <!----- Si la modification a fonctionnée on affiche un message de succès ---->
        <?php if(isset($success)):?>
        <div class="success">
            <?php echo $success;?>
        </div>
        <br />

        <a href="<?= base_url() ?>/admin">Retour au site</a>

        <!----- Sinon, on affiche le formulaire de modification ---->
        <?php else:?>
        <?=form_open('admin/modify'); ?>

            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" value="<?= $this->session->userdata('username')?>" />
            <br />
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" id="email" value="<?= $this->session->userdata('mail')?>" />
            <br />
            <label for="city">Ville :</label>
            <input type="text" name="city" id="autocomplete" value="<?= $this->session->userdata('city')?>" />
            <!--            <br />-->
            <button class="button2 icon-valid">
                <span>Enregistrer</span>
            </button>

            <?=form_close();?>

                <div class="errors">
                    <p>
                        <?=form_error( 'pseudo', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'email', '<span class="error">', '</span');?>
                    </p>
                    <p>
                        <?=form_error( 'city', '<span class="error">', '</span');?>
                    </p>
                </div>
                <a class="button2 icon-settings" href="<?=base_url() ?>admin/pw_change/">
                    <span>Modifier mon mot de passe</span>
                </a>
                <a class="button2 icon-delete" href="<?=base_url() ?>admin/delete/">
                    <span>Supprimer mon compte</span>
                </a>

                <?php endif; ?>
    </div>
</div>