<?php
$class = array();
$champs = array();
include_once 'admin_header.php';
$statistique = 0; // A 1 si afficher le bloc statistique
$require = 1; // Requis nécessaire
$folder = array(); // Les répertoires nécessaires
$indexAdmin = new ModuleAdmin();
$liste_requis = array('wideimage','gd2');
if ($statistique == 1){
    $indexAdmin->addInfoBox(constant('_AM_'.$moduleUP.'_STAT'));
    //@TODO:Voir si besoin des statistiques
}
if($xoopsModuleConfig[$DirName.'_todo']){
    $indexAdmin->addInfoBox(constant('_AM_'.$moduleUP.'_TODOLIST'));
    $a = 0;
    $temp = $$DirName->valeur_config('optimise') + ($duree_optimize * 86400 );
    if ( $temp === false) {
        $indexAdmin->addInfoBoxLine(constant('_AM_'.$moduleUP.'_TODOLIST'),CHG_VIDE,constant('_AM_'.$moduleUP.'_PASOPT'),'red');
    }else{
        if (time() > $temp){
            $indexAdmin->addInfoBoxLine(constant('_AM_'.$moduleUP.'_TODOLIST'),CHG_VIDE,constant('_AM_'.$moduleUP.'_OPT_AFAIRE').' '.$$DirName->gest_date($temp,3),'red');
        }else{
            $indexAdmin->addInfoBoxLine(constant('_AM_'.$moduleUP.'_TODOLIST'),CHG_VIDE,constant('_AM_'.$moduleUP.'_OPT_FAIT').' '.$$DirName->gest_date($temp,3),'green');
        }
    }
    unset($temp);
    $temp = $$DirName->valeur_config('sauvebdd') + ($duree_sauvebdd * 86400);
    if ($temp === false) {
        $indexAdmin->addInfoBoxLine(constant('_AM_'.$moduleUP.'_TODOLIST'),CHG_VIDE,constant('_AM_'.$moduleUP.'_PASSAUVE'),'red');
    }else {
        if (time() > $temp) { // sauvegarde tout les 7 jours
            $indexAdmin->addInfoBoxLine(
                constant('_AM_' . $moduleUP . '_TODOLIST'),
                CHG_VIDE,
                constant('_AM_' . $moduleUP . '_SAUVE_AFAIRE') . ' ' . $$DirName->gest_date($temp, 3),
                'red'
            );
        } else {
            $indexAdmin->addInfoBoxLine(
                constant('_AM_' . $moduleUP . '_TODOLIST'),
                CHG_VIDE,
                constant('_AM_' . $moduleUP . '_SAUVE_FAIT') . ' ' . $$DirName->gest_date($temp, 3),
                'green'
            );
        }
    }
}
if ($require == 1 && count($liste_requis) > 0){
    $indexAdmin->addInfoBox(constant('_AM_'.$moduleUP.'_REQUIS'));
    foreach($liste_requis as $v){
        $tableau = $$DirName->requis($v);
        if(!empty($tableau[0]['present'])){
            $indexAdmin->addInfoBoxLine(constant('_AM_'.$moduleUP.'_REQUIS'),$tableau[0]['titre'], $tableau[0]['present'], $tableau[0]['couleur']);
        }
    }
}
$folder = array($uri_sauve_bdd,URI_ITEM,URI_THUMB_CAT); // TODO: a compléter si nécessaire
if (count($folder)>0){
    foreach(array_keys($folder) as $i){
        $indexAdmin->addConfigBoxLine($folder[$i], 'folder');
        $indexAdmin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
    }
}
echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();
include 'admin_footer.php';