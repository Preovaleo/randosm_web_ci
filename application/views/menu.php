<?php if(isset($connected) && $connected){?>

<nav>
    <div class="menu">
        <?php
        switch ($status) {
            case "1":
                echo "<a"; if ($page_en_cours=='administration') echo " id='item-en-cours'"; echo " href=" . base_url() . "administration>Administration</a>";
                echo "<a"; if ($page_en_cours=='moderation') echo " id='item-en-cours'"; echo " href=" . base_url() . "moderation>Modération</a>";
            break;
        
            case "2":
            echo "<a"; if ($page_en_cours=='moderation') echo " id='item-en-cours'"; echo " href=" . base_url() . "moderation>Modération</a>";
            break;
        }
        
        ?>
        <a <?php if ($page_en_cours=='MesRandonnees') { echo "id='item-en-cours'";} ?>href="<?=base_url() ?>my_hikes">Mes randonnées</a>
    </div>
    <div class="menu">
        <a <?php if ($page_en_cours=='accueil') { echo "id='item-en-cours'";} ?>href="
            <?=base_url() ?>">Accueil</a>
        
        <a <?php if ($page_en_cours=='osm') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/osm">OpenStreetMap</a>
        <a <?php if ($page_en_cours=='aboutus') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/aboutus">Qui sommes-nous ?</a>
        <a <?php if ($page_en_cours=='faq') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/faq">FAQ</a>
        <a <?php if ($page_en_cours=='donate' ) { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/donate">Donation</a>
        <a <?php if ($page_en_cours=='PlayStore') { echo "id='item-en-cours'";} ?>href="<?=play_store_url?>">
            <img src="<?=base_url() ?>assets/images/playstore.svg" alt="Play Store">
        </a>
    </div>
</nav>

<?php } else {?>

<nav>
    <div class="menu">
        <a <?php if ($page_en_cours=='accueil') { echo "id='item-en-cours' ";} ?>href="<?=base_url() ?>">Accueil</a>
        <a <?php if ($page_en_cours=='osm') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/osm">OpenStreetMap</a>
        <a <?php if ($page_en_cours=='aboutus') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/aboutus">Qui sommes-nous ?</a>
        <a <?php if ($page_en_cours=='faq') { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/faq">FAQ</a>
        <a <?php if ($page_en_cours=='donate' ) { echo "id='item-en-cours'";} ?>href="<?=base_url()?>index/donate">Donation</a>
        <a <?php if ($page_en_cours=='PlayStore') { echo "id='item-en-cours'";} ?>href="<?=play_store_url?>"><!-- play_store_url() set in config.php -->
            <img src="<?=base_url() ?>assets/images/playstore.svg" alt="Play Store">
        </a>
    </div>
</nav>

<?php }?>