<div class='content'>
    <div class='content-box'>
        <h1>Administration</h1>
        <br/>
        <a class="button new-hike" href="<?=base_url() ?>administration/messages">Messages</a>
        <a class="button new-hike" href="<?=base_url() ?>administration/members">Membres</a>
        <br/>

        <?php if (!isset($mess)) { ?>
        <?php } elseif ($mess) { echo "<p><u>Messages :</u></p>"; if (is_array($messages)){ if (count($messages)==0) { ?>Il n'y a pas de message dans la base de données.
        <br/>
        <?php } else {?>
        <?=form_open( 'administration/delete_mess'); ?>
            <?php foreach ($messages as $message) { // Ce qu 'on affiche pour chaque message?>        
            <div class='tile '>
                <div class='tile-wrapper '>
                    <div style="float:left"><input type="checkbox" name="<?= $message->id ?>" ></div>
                    <a onclick="showMessage(<?= $message->id ?>)">
                    <div class='tile-column1 '>   
                        <?php
                        echo "<b>De : </b>". "\"" . $message->name . "\", ";
                        echo $message->mail . "<br>";
                        echo "<b>Sujet : </b>". $message->title . "<br>";
                        ?>                           
                        <div id="<?= $message->id ?>" style="display:none">
                            <br>
                            <?= $message->message ?>
                        </div>
                    </div>
                    </a>  
                    <div class="tile-column2">
                        <a class="button2 icon-pencil" href="<?= base_url().'administration/answer/'.$message->id ?>"><span>Répondre</span></a>
                    </div>
                </div>
            </div>
            <?php } ?>
            <input class="button" type="submit" name="del" value="Supprimer la sélection" />
            <?=form_close(); ?>
         <?php }} ?>
        
<?php } elseif (!$mess) { 
        echo "<p><u>Utilisateurs :</u></p>";
        if (is_array($users)){
         foreach ($users as $tmp) { // Ce qu'on affiche pour chaque utilisateur?>
            <div class='tile'>
                <div class='tile-wrapper'>
                    <div class='tile-column1'>
                        <?php echo "<b>Nom : </b>". $tmp->name . " | "; echo "
                        <b>Mail : </b>". $tmp->mail . "
                        <br/>"; if (isset($tmp->city)) { echo "
                        <b>Ville : </b>". $tmp->city . "
                        <br/>"; } switch ($tmp->state) { case "0": echo "
                        <b>Statut : </b>Membre (compte non validé)"; ?>
                        <br/>
                        <a href="<?=base_url() ?>administration/newMail/<?=$tmp->name?>">Renvoyer un mail de confirmation</a>
                        <?php break; case "1": echo "<b>Statut : </b>Administrateur"; break; case "2": echo "<b>Statut : </b>Modérateur"; break; case "3": echo "<b>Statut : </b>Membre"; break; } ?>
                    </div>
                    <div class="tile-column2">
                        <a class="icons-buttons icon-up" href="<?=base_url() ?>administration/promote/<?php echo $tmp->user_id ?>"></a>
                        <a class="icons-buttons icon-down" href="<?=base_url() ?>administration/demote/<?php echo $tmp->user_id ?>"></a>
                        <a class="icons-buttons icon-delete" href="<?=base_url() ?>administration/confirm/<?php echo $tmp->user_id ?>"></a>
                    </div>

                </div>
            </div>

            <?php }} ?>
            <?php } ?>
    </div>
</div>

<script>
    var hidden = true;

    function showMessage($id) {
        if (hidden) {
            $('#' + $id).fadeIn();
            hidden = false;
        } else {
            $('#' + $id).fadeOut();
            hidden = true;
        }
    }
</script>