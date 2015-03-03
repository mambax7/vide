<?php
/**
 * CHG-WEB - MONTUY33513 - 2014-08-15
 */
$temp = array('admin');
if (isset($class) && is_array($class)){
    $class = array_merge($temp,$class);
}else{
    $class = $temp;
}
unset($temp);
include_once '../../../mainfile.php';
include_once '../include/config.php';
include_once '../include/start.php';
include_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
require_once XOOPS_ROOT_PATH . '/include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
global $xoopsModule;
$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');
// Load language files
xoops_loadLanguage('admin', $DirName);
xoops_loadLanguage('modinfo', $DirName);
xoops_loadLanguage('main',$DirName);
xoops_loadLanguage('commun',$DirName);
if (file_exists($GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php'))) {
    include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');
} else {
    redirect_header('../../../admin.php', 5, CHG_MODULEADMIN_MISSING, FALSE);
    exit();
}
$myts =& MyTextSanitizer::getInstance();
if ($xoopsUser) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
    $usid = $xoopsUser->uid();
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
    exit();
}
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH.'/class/template.php');
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->assign('pathIcon16', $pathIcon16);
$$DirName->add_admin_header('<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$DirName.'/css/style.css" />');
xoops_cp_header();
