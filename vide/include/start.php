<?php
/**
 * CHG-WEB - MONTUY337513 - 2014-08-15
 */
if (!defined('XOOPS_ROOT_PATH')) die('XOOPS root path not defined');
switch ($type_gestion) {
    case '1':
        if (PHP_VERSION_ID < 50400) error_reporting (E_ALL | E_STRICT);
        else error_reporting (E_ALL);      //filtrage des erreurs reportées
        ini_set('display_errors', true);      //affichage des erreurs
        ini_set('html_errors', false);       //désactivation des liens html dans les erreurs
        ini_set('display_startup_errors',true);     //affichage des erreurs de démarrage
        ini_set('log_errors', false);       //création d'un fichier de log
        ini_set('error_prepend_string','<span="color: red;">'); //début mise en forme erreur
        ini_set('error_append_string','<br /></span>');    //fin mise en forme erreur
        ini_set('ignore_repeated_errors', true);     //ignorer les erreurs répétées
        break;
    case '2':
        error_reporting (E_ALL);        //filtrage des erreurs reportées
        ini_set('display_errors', false);      //affichage des erreurs
        ini_set('html_errors', false);       //désactivation des liens html dans les erreurs
        ini_set('display_startup_errors',false);    //affichage des erreurs de démarrage
        ini_set('log_errors', true);       //création d'un fichier de log
        ini_set('error_log', XOOPS_ROOT_PATH.'/modules/'.$DirName.'/log/error.log');    //localisation du fichier de log
        ini_set('error_prepend_string','<span="color: red;">'); //début mise en forme erreur
        ini_set('error_append_string','</span>');    //fin mise en forme erreur
        ini_set('ignore_repeated_errors', true);    //ignorer les erreurs répétées
        break;
    default:
        error_reporting (E_ALL);        //filtrage des erreurs reportées
        ini_set('display_errors', false);      //affichage des erreurs
        ini_set('html_errors', false);       //désactivation des liens html dans les erreurs
        ini_set('display_startup_errors',false);    //affichage des erreurs de démarrage
        ini_set('log_errors', false);
}
include_once(XOOPS_ROOT_PATH.'/modules/'.$DirName.'/class/class_class.php');
if(array_key_exists('HTTP_REFERER',$_SERVER)){
    $$DirName->en_provenance = $_SERVER['HTTP_REFERER'];
} else {
    $$DirName->en_provenance = 'direct';
}
$$DirName->url_en_cours = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$$DirName->nom_script = $_SERVER['SCRIPT_NAME'];
$op = $$DirName->requete('post','op',$op);
if (empty($op)){
    $op = $$DirName->requete('get','op',$op);
}
$id = $$DirName->requete('post','id',$id);
if ($id == 0){
    $id = $$DirName->requete('get','id',$id);
}
$page = $$DirName->requete('get','page',$page);
if($xoopsUser){
    $$DirName->utilisateur = $xoopsUser->getVar('uid');
}