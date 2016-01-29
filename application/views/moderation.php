<div class='content'>
    <div class='content-box'>
        <h1>Modération</h1>

        <?php if(isset($unmoderatedHikes)) { echo "<p>Randonnées à modérer :</p>"; foreach ($unmoderatedHikes as $hike) { // Ce que lon affiche pour chaque randonnée non modérée?>        
            <div class='tile '>
                <div class='tile-wrapper '>
                    <div class='tile-column1 '>
                        <?php
                        echo "<b>Créateur : </b>". ucfirst($hike->creator) . "<br>";
                        echo "<b>Nom : </b>". $hike->name . "<br>";
                        echo "<b>Type : </b>". $hike->type . "<br>";
                        echo "<b>Longueur : </b>" .   $hike->distance . " Km<br>";
                        echo "<b>Durée : </b>" .      $hike->time . "<br>"; 
                        echo "<b>Ville : </b>" . $hike->city->libCity . " (" . $hike->city->numDep . ")<br>";
                        echo "<b>Description : </b>" . $hike->description . "<br>";
                        ?>
                    </div>

                    <div class="tile-column2">
                        <div class="tile-column2-buttons">
                            <a class="button2 icon-valid" href="<?= base_url() ?>moderation/changeHikeState/<?=$hike->randonnee_id ?>/1"><span>Accepter</span></a>
                            <a class="button2 icon-refuse" href="<?= base_url() ?>moderation/changeHikeState/<?=$hike->randonnee_id ?>/2"><span>Refuser</span></a>
                        </div>
                    </div>

                </div>
            </div>
            
        <?php }} else { 
                echo "Toutes les randonnées sont actuellement modérées.";
                } ?>
    </div>
</div>