<?php
/**
 * CHG-WEB - MONTUY337513 - 2014-08-15
 */
// Paramètres
$DirName = basename(dirname(__DIR__));
$moduleUP = strtoupper($DirName);
if(!isset($AdminDirName)) $AdminDirName = $DirName.'/admin';
$uri_module = XOOPS_ROOT_PATH.'/modules/'.$DirName;
$uri_sauve_bdd = XOOPS_ROOT_PATH.'/uploads/sauve';
if (!defined('URI_THUMB_CAT')) define('URI_THUMB_CAT',XOOPS_ROOT_PATH.'/uploads/'.$DirName.'/thumb_cat');
if (!defined('URL_THUMB_CAT')) define('URL_THUMB_CAT',XOOPS_URL.'/uploads/'.$DirName.'/thumb_cat');
if (!defined('URI_ITEM') )define('URI_ITEM',XOOPS_ROOT_PATH.'/uploads/'.$DirName.'/item');
if (!defined('URL_ITEM')) define('URL_ITEM',XOOPS_URL.'/uploads/'.$DirName.'/item');
$type_gestion = 1; // 1=>mode debug, 2=>mode production (erreur dans log/error.log), 0=>Aucun traitement
// Liste des tables raccourci_table => nom_table //
$liste_table = array( 
    'log' => $DirName.'_log',
    'divers'  =>  $DirName.'_divers',
    'categorie' => $DirName.'_categorie',
    'item' => $DirName.'_item',
);
// Champs query_string commun // Ce tableau sert a préciser les variables autorisés par POST,GET,COOKIE
$query_string_commun = array(
    'id' => 'int',
    'op' => 'string',
    'page' => 'int',
    'tri' => 'string',
    'order' => 'string'
);

// Variables générales
$id = 0;
$op = '';
$page = 1;
$content = '';
$duree_optimize = 7; // En jours
$duree_sauvebdd = 7; // En jours
// Liste des class communes a chargés
$liste_class = array(
    'kernel',
);
if (isset($class) && is_array($class)){
    $liste_class = array_merge($liste_class,$class);
}
// Paramètres requis
$min_php = '5.3';
$min_xoops = '2.5.6';
$min_admin = '1.1';
$min_db = array('mysql' => '5.1', 'mysqli' => '5.1');

// Paramètres du module
global $xoopsModule;
$tableau_erreur = array();
$hasAdmin = 1; // Mettre à 1 si interface administration
$system_menu = 1; // Mettre à 1 si Xoops gère le menu admin
// Menu admin du menu
$menu_admin = array();
/*$menu_admin[] = array(
    'title' => 'essai',
    'link' => 'admin/index.php',
    'icon' => $pathIcon32.'/index.png'
);*/
$allow_mimetype_cat = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
$allow_mimetype_item = array('application/pdf');