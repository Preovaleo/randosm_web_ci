<div class="content">
    <div class="subscribe-box content-box">
        <h1>Inscription</h1>
        
        <!-- Si l'inscription a fonctionnée on affiche un message de succès -->
        <?php if(isset($success)):?>
                <div class="success">
                    <?php echo $success;?>
                </div><br />
                
                <a href="<?= base_url() ?>">Retour au site</a>
                
        <!-- Sinon, on affiche le formulaire d'inscription -->
        <?php else:?>
                
                <?=form_open('signup');?>
                
                <label for="name">Pseudo *</label>
                <input type="text" name="name" id="name" value="<?= set_value('name');?>" />
                <br />
                <label for="email">Adresse e-mail *</label>
                <input type="email" name="email" id="email" value="<?= set_value('email');?>" />
                <br />
                <label for="pass">Mot de passe *</label>
                <input type="password" name="pass" id="pass" value="<?= set_value('pass');?>" />
                <br />
                <label for="pass2">Vérification du mot de passe *</label>
                <input type="password" name="pass2" id="pass2" value="<?= set_value('pass');?>" />
                <br>
                <input type="submit" value="M'inscrire" class="button" />
                
                <p>*Informations requises</p>
                <?=form_close();?>
                
                
                <div class="errors">
                    <p><?=form_error( 'name', '<span class="error">', '</span');?></p>
                    <p><?=form_error( 'email', '<span class="error">', '</span');?></p>
                    <p><?=form_error( 'pass', '<span class="error">', '</span');?></p>
                    <p><?=form_error( 'pass2', '<span class="error">', '</span');?></p>
                    <p><?php if(isset($error_captcha)){ echo $error_captcha;}?>  
                    </p>
                </div>
        
        <?php endif; ?>
    </div>
</div>
