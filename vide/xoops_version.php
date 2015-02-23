<?php
/**
 * CHG-WEB - MONTUY337513 - 2014-08-15 
 */
if (!defined('XOOPS_ROOT_PATH')) die('XOOPS root path not defined');
require __DIR__.'/include/config.php';
if(!isset($DirName)) $DirName = basename( __DIR__ );
if(!isset($AdminDirName)) $AdminDirName = $DirName.'/admin';
if (!isset($moduleUP))$moduleUP = strtoupper($DirName);
if (!function_exists('chg_parse_function')){
    include_once __DIR__.'/include/function.special.php';
}
$modversion['name'] = constant('_MI_'.$moduleUP.'_NAME');
$modversion['version'] = constant('_MI_'.$moduleUP.'_VERSION');
$modversion['description'] = constant('_MI_'.$moduleUP.'_DESC');
$modversion['author'] = constant('_MI_'.$moduleUP.'_AUTHOR_NAME');
$modversion['credits'] = constant('_MI_'.$moduleUP.'_CREDIT');
$modversion['help'] = 'page=help';
$modversion['license'] = constant('_MI_'.$moduleUP.'_LICENCE');
$modversion['license_url'] = constant('_MI_'.$moduleUP.'_LICENCE_URL');
$modversion['official'] = 0;
$modversion['image'] = 'images/'.$DirName.'_slogo.png';
$modversion['dirname'] = $DirName;
$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
$modversion['onInstall'] = (is_file('include/install_function.php')) ? 'include/install_function.php' : '';
$modversion['onUpdate'] = (is_file('include/update_function.php')) ? 'include/update_function.php' : '';
//about
$modversion['release_date']        = constant('_MI_'.$moduleUP.'_DATE_RELEASE');
$modversion['module_website_url']  = constant('_MI_'.$moduleUP.'_MODULE_WEB_URL');
$modversion['module_website_name'] = constant('_MI_'.$moduleUP.'_MODULE_WEB_NAME');
$modversion['module_status']       = constant('_MI_'.$moduleUP.'_STATUS');
$modversion['author_website_url'] = constant('_MI_'.$moduleUP.'_AUTHOR_URL');
$modversion['author_website_name'] = constant('_MI_'.$moduleUP.'_AUTHOR_NAME');
$modversion['submit_bug'] = constant('_MI_'.$moduleUP.'_SUBMIT_BUG');
$modversion['submit_feature'] = constant('_MI_'.$moduleUP.'_SUBMIT_FEATURE');
$modversion['demo_site_url'] =  constant('_MI_'.$moduleUP.'_MODULE_DEMO_URL');
$modversion['demo_site_name'] = constant('_MI_'.$moduleUP.'_MODULE_DEMO_NAME');
$modversion['people']['developers'][] = constant('_MI_'.$moduleUP.'_DEV');
$modversion['people']['testers'][] = constant('_MI_'.$moduleUP.'_TEST');
$modversion['people']['translaters'][] = constant('_MI_'.$moduleUP.'_TRANSLATE');
$modversion['people']['documenters'][] = constant('_MI_'.$moduleUP.'_DOCU');
$modversion['min_php'] = $min_php;
$modversion['min_xoops'] = $min_xoops;
$modversion['min_admin'] = $min_admin;
$modversion['min_db'] = $min_db;
// tables mysql
$i = 0;
foreach ($liste_table as $v){
    $modversion['tables'][$i] = $v;
    $i++;
}
unset($i);
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
//Menu
$modversion['hasMain'] = 1; //
//// partie spécifique pour la gestion des droits - a commenter si pas besoin (en exemple)
$cansubmit = 0;
$module_handler =& xoops_gethandler('module');
$module1 =& $module_handler->getByDirname($modversion['dirname']);
if ($module1) {
    global $xoopsUser;
    if (is_object($xoopsUser)) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gperm_handler =& xoops_gethandler('groupperm');
    if ($gperm_handler->checkRight($DirName.'_submit', 0, $groups, $module1->getVar('mid'))) {
        $cansubmit = 1;
    }
}
$i = 1;
$modversion['sub'][$i]['name'] = constant('_MI_'.$moduleUP.'_MENU_SEARCH');
$modversion['sub'][$i]['url'] = 'search.php';
if($cansubmit == 1){
    $i++;
    $modversion['sub'][$i]['name'] = constant('_MI_'.$moduleUP.'_MENU_ADD');
    $modversion['sub'][$i]['url'] = 'add_item.php';
}
unset($i);
// Menu administration
$modversion['hasAdmin'] = $hasAdmin;
$modversion['system_menu'] = $system_menu;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
// Include dans la recherche de Xoops
$modversion['hasSearch'] = 0;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = $DirName.'_search';
// Options
$i = 1;
$modversion['config'][$i]['name'] = $DirName.'_lcen';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_LCEN';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_LCEN_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['options'] = array();
$i++;
$modversion['config'][$i]['name'] = $DirName.'_nb_comindex';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_COMINDEX';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_COMINDEX_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$modversion['config'][$i]['options'] = array('10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '60' => 60);
//$modversion['config'][$i]['category'] = 'index';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_nb_com';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_COM';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_COM_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$modversion['config'][$i]['options'] = array('10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '60' => 60);
$i++;
$modversion['config'][$i]['name'] = $DirName.'_title';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_TITLE';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_TITLE_DSC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '_MI_'.$moduleUP.'_TITRE';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_keywords';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_KEYWORDS';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_KEYWORDS_DSC';
$modversion['config'][$i]['formtype'] = 'textarea' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '_MI_'.$moduleUP.'_MOTCLE';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_description';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_METADESCRIPTION';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_METADESCRIPTION_DSC';
$modversion['config'][$i]['formtype'] = 'textarea' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '_MI_'.$moduleUP.'_DESCRIPTION';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_todo';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_TODO';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_TODO_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['options'] = array();
$i++;
$modversion['config'][$i]['name'] = $DirName.'_jquery';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_JQUERY';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_JQUERY_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['options'] = array();
$i++;
$modversion['config'][$i]['name'] = $DirName.'_maxfilesize';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_MAX_TAILLE_ITEM';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_MAX_TAILLE_ITEM_DESC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '8000';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_width';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_MAX_LARGEUR_PHOTO';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_MAX_LARGEUR_PHOTO_DESC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '8000';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_height';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_MAX_HAUTEUR_PHOTO';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_MAX_HAUTEUR_PHOTO_DESC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '8000';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_width_thumb';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_LARGEUR_THUMB';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_LARGEUR_THUMB_DESC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '100';
$i++;
$modversion['config'][$i]['name'] = $DirName.'_height_thumb';
$modversion['config'][$i]['title'] = '_MI_'.$moduleUP.'_HAUTEUR_THUMB';
$modversion['config'][$i]['description'] = '_MI_'.$moduleUP.'_HAUTEUR_THUM_DESC';
$modversion['config'][$i]['formtype'] = 'textbox' ;
$modversion['config'][$i]['valuetype'] = 'text' ;
$modversion['config'][$i]['default'] = '100';
unset($i);
// Templates
$i = 1;
$modversion['templates'][$i]['file'] = $DirName.'_index.html';
$modversion['templates'][$i]['description'] = constant('_MI_'.$moduleUP.'_TEMPLATE_INDEX_DSC');
$i++;
$modversion['templates'][$i]['file'] = $DirName.'_content.html';
$modversion['templates'][$i]['description'] = constant('_MI_'.$moduleUP.'_TEMPLATE_CONTENT_DSC');
$i++;
$modversion['templates'][$i]['file'] = $DirName.'_footer.html';
$modversion['templates'][$i]['description'] = constant('_MI_'.$moduleUP.'_TEMPLATE_FOOTER_DSC');
unset($i);
// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = $DirName.'_search';

// Smarty
$modversion['use_smarty'] = 1;




