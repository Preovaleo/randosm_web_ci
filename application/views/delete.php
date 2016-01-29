<?php $page_en_cours = 'MonCompte'; ?>
    <div class='content'>
        <div class='content-box'>
            <h1>Supprimer mon compte</h1>
            <?php if ($conf==false) { ?>
				<br/>
                Etes-vous sûr de vouloir supprimer votre compte ? <br /><br />
                    <a class="button" href="<?= base_url() ?>admin/delete/true">Oui</a>
                    <a class="button" href="<?= base_url() ?>admin/">Annuler</a>        
            <?php } else { ?>
				<br/>
                Votre compte a été supprimé. <br /><br />
                    <a class="button" href="<?= base_url() ?>">Retour au site</a>
            <?php } ?>
        </div>
    </div>