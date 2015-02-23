<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
global $xoopsModuleConfig;
include_once XOOPS_ROOT_PATH.'/modules/'.$DirName.'/class/class_loadclass.php';
$$DirName = load_class::execute($liste_class);
