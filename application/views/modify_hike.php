<div class='content'>
    <div class='trek-making-box content-box'>
        <h1>Modifier une randonnée</h1>
        <?php echo validation_errors(); ?>
        <form ng-app="RandOSM-CreateHike" ng-controller="StepsCtrl" action="aaaaaaaaaa????????" method="post" name="create-hike-form" id="create-hike-form">
            <!-- ####################################################### STEP 1 ############################################################## -->
            <div id="step1">
                <div class='step-progress'>
                    <h2>Étape 1/6</h2>
                    <progress id="avancement" value="1" max="6"></progress>
                </div>
                <label for="name">Nom</label>
                <input type="text" name="name" value="<?= $name ?>" />
                <br>
                <label for="city">Ville associée</label>
                <span class="infos">Il s'agit de la ville dans laquelle la randonnée se pratique.</span>
                <input type="text" name="city" id="autocomplete" autocomplete="off" value="<?= $city->libCity . " (" . $city->numDep . ")" ?>" />
                <br>
                <div id="results" /></div>
            <a class="button next disabled-button step2">Étape suivante</a>
            <div class="errors"></div>

    </div>


    <!-- ############################################################ STEP 2 ################################################################ -->
    <div id="step2">
        <div class='step-progress'>
            <h2>Étape 2/6</h2>
            <progress id="avancement" value="2" max="6"></progress>
        </div>
        <div id="form-step-2">
            <label for="type">Type</label>
            <select name="type" id='type' onChange='changeType()'>
                <option value="Canoë/Kayak">Canoë/Kayak</option>
                <option value="Équitation">Équitation</option>
                <option value="Marche" selected>Marche</option>
                <option value="Monocycle">Monocycle</option>
                <option value="Quad">Quad</option>
                <option value="Raquettes">Raquettes</option>
                <option value="Vélo">Vélo</option>
                <option value="VTT">VTT</option>
                <option value="Autre :">Autre :</option>
            </select>
            <br>
            <label for="difficulty">Difficulté</label>
            <div class="radio_buttons">
                <input type="radio" name="difficulty" value="1" />1
                <input type="radio" name="difficulty" value="2" />2
                <input type="radio" name="difficulty" value="3" checked="checked" />3
                <input type="radio" name="difficulty" value="4" />4
                <input type="radio" name="difficulty" value="5" />5</div>
        </div>
        <a class="button step1 prev">Étape précédente</a>
        <a class="button step3 next">Étape suivante</a>
    </div>


    <!-- ############################################################# STEP 3 ################################################################ -->
    <div id="step3">
        <div class='step-progress'>
            <h2>Étape 3/6</h2>
            <progress id="avancement" value="3" max="6"></progress>
        </div>

        <div id="map"></div>
        <div id="steps">
            <h1>
                <ng-pluralize count="steps.length" when="{'0': 'Étapes', 'one': '1 Étape', 'other': '{} Étapes'}"></ng-pluralize>
            </h1>
            <div id="stepsContent">

                <div class="step bounce" ng-repeat="step in steps">
                    <p>
                        <div class="step-number" ng-bind="steps.indexOf(step)+1"></div>
                        <div class="indications">
                            <span>Étape n
                                <sup>o</sup>
                                <span ng-bind="(steps.indexOf(step))+1"></span>
                            </span>
                            <input type="text" placeholder="Un commentaire ou une indication pour cette étape ?" ng-model="step.comment">
                        </div>
                    </p>
                </div>

            </div>

        </div>

        <a class="button step2 prev">Étape précédente</a>
        <a class="button step4 next" ng-click="sendLength()">Étape suivante</a>
    </div>


    <!-- ############################################################ STEP 4 ################################################################ -->
    <div id="step4">
        <div class='step-progress'>
            <h2>Étape 4/6</h2>
            <progress id="avancement" value="4" max="6"></progress>
        </div>


        <label for="distance">Distance (en Km)</label>
        <span class="infos">La distance a été calculée en fonction des points que vous avez placé à l'étape précédente, mais vous pouvez l'ajuster.</span>
        <input type="text" name="length" id="length" value="<?= $distance ?>" required="length" />

        <br>
        <label for="time">Durée</label>
        <span class="infos">Cette durée doit correspondre au type de randonnée que vous avez choisi à l'étape 2. (Format Heures:Minutes)</span>
        <input type="time" name="time" id="time" value="<?= $time ?>" required="time" />
        <a class="button step3">Étape précédente</a>
        <a class="button step5">Étape suivante</a>
        <div class="errors"></div>
    </div>


    <!-- ############################################################ STEP 5 ################################################################ -->
    <div id="step5">
        <div class='step-progress'>
            <h2>Étape 5/6</h2>
            <progress id="avancement" value="5" max="6"></progress>
        </div>

        <label for="description">Description</label>
        <textarea name="description" id="description" value="<?= $description  ?>" rows="10" cols="40"></textarea>
        <a class="button step4 prev">Étape précédente</a>
        <a class="button step6 next">Étape suivante</a>

        <!--            <input class='button' type='submit' value='Créer la randonnée' />-->
    </div>


    <!-- ############################################################ STEP 6 ################################################################ -->
    <div id="step6">
        <div class='step-progress'>
            <h2>Étape 6/6</h2>
            <progress id="avancement" value="6" max="6"></progress>
        </div>

        <h1>
            <i class="icon-arrow"></i>Fichiers photos</h1>
        <div id="photo-dropzone" class="dropzone">
            <p>3 photos maximum
                <br />Format acceptés : jpg, jpeg, png. Taille max par fichier : 3 Mo.</p>
        </div>

        <h1>
            <i class="icon-arrow"></i>Fichiers audios</h1>
        <div id="audio-dropzone" class="dropzone">
            <p>3 fichiers audios maximum
                <br />Format acceptés : mp3, m4a, wav, etc. Taille max par fichier : 1 Mo.</p>
        </div>

        <h1>
            <i class="icon-arrow"></i>Fichiers vidéos</h1>
        <div id="video-dropzone" class="dropzone">
            <p>3 vidéos maximum
                <br />Format acceptés : avi, mp4, mpeg, mov, etc. Taille max par fichier : 3 Mo.</p>
        </div>



        <a class="button step5">Étape précédente</a>
        <a class="button" ng-click="postInfos()">Créer la randonnée</a>
    </div>

    </form>
</div>
<!-- Close div.content -->


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular-animate.min.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?=base_url() ?>assets/css/jquery-ui.css">

<!---------------------------- Carte OpenStreetMap ---------------------------->
<script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />

<script src="<?= base_url() ?>assets/js/map.js"></script>
<link href="<?= base_url() ?>assets/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="<?= base_url() ?>assets/js/dropzone.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/create_hike.js"></script>
