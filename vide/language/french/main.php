<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
if (!isset($DirName) OR $DirName != basename(dirname(dirname( __DIR__ )))){
    $DirName = basename(dirname(dirname(__DIR__)));
}
if (!isset($moduleUP)) $moduleUP = strtoupper($DirName);

define('_MA_'.$moduleUP.'_CAT','Catégorie(s)');
define('_MA_'.$moduleUP.'_TITRE_INDEX','Gestionnaire de PDF');
define('_MA_'.$moduleUP.'_FILE','Liste des documents consultables');
