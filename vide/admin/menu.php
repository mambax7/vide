<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
include_once XOOPS_ROOT_PATH.'/mainfile.php';
if (!isset($DirName)){
    $DirName = basename(dirname(__DIR__ ));
}
if (!isset($moduleUP)){
    $moduleUP = strtoupper($DirName);
}
if (!defined('CHG_ACCUEIL')) xoops_loadlanguage('commun');
xoops_loadlanguage('main');
if (!isset($module2) OR !is_object($module2)){
    $module_handler  = xoops_gethandler('module');
    $module2 = $module_handler->getByDirname($DirName );
}
$pathIcon32      = $module2->getInfo('icons32');
$pathModuleAdmin = $module2->getInfo('dirmoduleadmin');

$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = CHG_ACCUEIL;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/index.png';
$i++;
$adminmenu[$i]['title'] = constant('_MI_'.$moduleUP.'_MENU_CAT');
$adminmenu[$i]['link'] = 'admin/categorie.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/category.png';
$i++;
$adminmenu[$i]['title'] = constant('_MI_'.$moduleUP.'_MENU_ITEM');
$adminmenu[$i]['link'] = 'admin/item.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/content.png';
if (!isset($menu_admin)) include XOOPS_ROOT_PATH.'/modules/'.$DirName.'/include/config.php';
foreach($menu_admin as $v){
    $i++;
    $adminmenu[$i] = $v;
}
$i++;
$adminmenu[$i]['title'] = CHG_MAINT;
$adminmenu[$i]['link'] = 'admin/maintenance.php';
$adminmenu[$i]['icon'] = '/images/icons/maintenance.png';
$i++;
$adminmenu[$i]['title'] = CHG_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/about.png';
unset($i);