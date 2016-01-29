<div class='content'>
    <div class='my-hikes-box content-box' ng-app="RandOSM-CreateHike" ng-controller="StepsCtrl">
        <h1>Mes randonnées</h1>
        
        <?php foreach ($userHikes as $hike) { // Ce que l'on affiche pour chaque randonnée ###################################?> 
        
        <div class="tile">
            <div class='tile-message'>
                <?php
                switch ($hike->moderated) {
                    case "0": echo "<span class='case0 icon-ellipsis'></span>"; break;
                    case "1": echo "<span class='case1 icon-valid'></span>"; break;
                    case "2": echo "<span class='case2 icon-refuse'></span>"; break;
                }
                ?>
            </div>
            <div class="tile-wrapper">
                <form class="modify-hike-form" method="post" action="<?=base_url() ?>my_hikes/modify/<?=$hike->randonnee_id ?>">
                    <div class='tile-column1'>
                        <b>Nom : </b><span  name='name' class="name-field"><?php echo $hike->name ?></span><br>
                        <b>Type : </b><span  name='type' class="type-field"><?php echo $hike->type ?></span><br>
                        <b>Difficulté : </b>
                        <span class="shoes"><?php
                    $d = $hike->difficulty;
    for ($i=0; $i<$d; $i++){ ?>
                            <i class='icon-shoe'></i>
                            <?php }
    for ($i=0; $i<(5-$d); $i++) { ?>
                            <i class='icon-shoe2'></i>
                            <?php } ?>
                        </span>
                        <span class="hide difficulty-radio"><?php echo $hike->difficulty ?></span>
                        <br>
                        <b>Longueur : </b><span name='distance' class='distance-field'><?php echo $hike->distance ?></span> Km<br>
                        <b>Durée : </b><span name='time' class='time-field'><?php echo $hike->time ?></span><br>
                        <b>Ville : </b><span name='city' class='city-field'><?php echo $hike->city->libCity ?> (<?php echo $hike->city->numDep ?>)</span><br>
                        <b>Date de création : </b><?php echo $hike->date_of_creation ?></span>
                </div>
                <div class="tile-column2">             
                    <div class="tile-column2-buttons">             
                        
                        
                        <a class="button2 icon-settings" ng-click="modifyInfos($event.target, <?=$hike->randonnee_id ?>)"><span>Modifier</span></a>
                        <a class="button2 icon-valid" style="display:none" ng-click="modifyHike($event.target, <?=$hike->randonnee_id ?>)"><span>Valider</span></a>
<!--                        <a class="button2 icon-refuse" style="display:none" ng-click="cancelModifying($event.target, <?=$hike->randonnee_id ?>)"><span>Annuler</span></a>-->
                        <a class="button2 icon-refuse" style="display:none" href="<?=base_url() ?>my_hikes"><span>Annuler</span></a>
                        <a class="button2 icon-map" style="display:none" ng-click="modifyMap($event.target, <?=$hike->randonnee_id ?>)"><span>Modifier les étapes</span></a>
                        <a class="button2 icon-delete" href="<?=base_url() ?>my_hikes/delete/<?=$hike->randonnee_id ?>"><span>Supprimer</span></a>
                    </div>
                </div>
                <div class="hike-description hide"><span id='description-field'>
                    <?php
        if($hike->description!="") {     
        echo $hike->description;
    } else { 
        echo "(Pas de description)";
    } ?>
                    </span></div>
                <div class="hike-pictures hide"></div>
                
            </form>
        </div>
        <div id="map" class="hide"><div id="help"></div></div>
        <button class="expand-bar show" ng-click="showDetails($event.target, <?=$hike->randonnee_id ?>)"><i class="icon-caret-down"></i></button>
        <button class="expand-bar hide" style="display:none" ng-click="hideDetails($event.target, <?=$hike->randonnee_id ?>)"><i class="icon-caret-up"></i></button>
    </div>
    <!--        Fin de ce que l'on affiche pour chaque randonnée ##################################################################################################-->
    
    <?php } ?>
    <a class="button2 icon-plus" href="<?=base_url() ?>my_hikes/create_hike"><span>Nouvelle randonnée</span></a>
</div> <!-- END my-treks-box content-box -->
</div> <!-- END content -->
 
<script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>