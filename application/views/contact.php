<div class="content">
    <div class="contact-box content-box">
        <h1>Contact</h1>
        <!----- Si l'envoi a fonctionné on affiche un message de succès ---->
        <?php if(isset($success)):?>
        <div class="success"><br>
            <?php echo $success;?>
        </div><br>
        
        <!----- Sinon, on affiche le formulaire de contact ---->
        <?php else:?>
        
        <?=form_open('index/contact'); ?>
        
        <input type="text" name="name" id="name" placeholder="Votre nom..." value="<?= $name?>" />
        <br>
        <input type="email" name="email" id="email" placeholder="Votre email..." value="<?= $mail?>" />
        <br>
        <input type="text" name="title" id="text" placeholder="Sujet..." value="<?= $title?>"/>
        <br>
        <textarea name="message" placeholder="Message..." rows="10" cols="38"><?= $message?></textarea>
        <br>
        <center><?php echo $recaptcha_html; ?></center>
        <br>
        <input type="submit" value="Envoyer" class="button" />
        <?=form_close();?>
        
        
        <div class="errors">
            <p><?=form_error( 'name', '<span class="error">', '</span');?></p>
            <p><?=form_error( 'email', '<span class="error">', '</span');?></p>
            <p><?=form_error( 'title', '<span class="error">', '</span');?></p>
            <p><?=form_error( 'message', '<span class="error">', '</span');?></p>
            <p><?php if(isset($error_captcha)){ echo $error_captcha;}?>  
        </div>
        
        <?php endif; ?>
    </div>
</div>
