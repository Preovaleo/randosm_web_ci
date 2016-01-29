<div class="content">
    <div class="contact-box content-box">
        <h1>Répondre</h1>
        <!----- Si l'envoi a fonctionné on affiche un message de succès ---->
        <?php if(isset($success)):?>
        <div class="success"><br>
            <?php echo $success;?>
            <br/><br/>
            <a class="button" href="<?= base_url() ?>administration/messages">Retour aux messages</a>   
        </div><br>
        
        <!----- Sinon, on affiche le formulaire de contact ---->
        <?php else:?>
        
        <?=form_open('administration/answer/'.$id); ?>
        
        <input type="text" name="name" id="name" placeholder="Nom..." value="<?= $name?>" />
        <br>
        <input type="text" name="title" id="text" placeholder="Sujet..." value="<?= $title?>"/>
        <br>
        <textarea name="message" placeholder="Message..." rows="10" cols="38"><?= $message?></textarea>
        <br>
        <input type="submit" value="Envoyer" class="button" />
        <a class="button" href="<?= base_url() ?>administration/messages">Retour</a> 
        <?=form_close();?>
        
        
        <div class="errors">
            <p><?=form_error( 'name', '<span class="error">', '</span');?></p>
            <p><?=form_error( 'title', '<span class="error">', '</span');?></p>
            <p><?=form_error( 'message', '<span class="error">', '</span');?></p>
        </div>
        
        <?php endif; ?>
    </div>
</div>