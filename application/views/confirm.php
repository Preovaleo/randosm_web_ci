    <div class='content'>
        <div class='content-box'>
            <h1>Supprimer le compte</h1>
            <?php if ($conf==false) { ?>
				<br/>
                Etes-vous sûr de vouloir supprimer le compte de <?php echo $name ?> ? <br /><br />
                    <a class="button" href="<?= base_url() ?>administration/confirm/<?php echo $userId ?>/true">Oui</a>
                    <a class="button" href="<?= base_url() ?>administration/members">Annuler</a>        
            <?php } else { ?>
				<br/>
                Le compte a été supprimé. <br /><br />
                    <a class="button" href="<?= base_url() ?>administration/members">Retour à l'administration</a>
            <?php } ?>
        </div>
    </div>