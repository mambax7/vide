<?php
if ( !include('../../mainfile.php') ) {
    die('XOOPS root path not defined');
}
global $xoopsModuleConfig;
include_once './include/config.php';

include_once './include/start.php';
// Load language files
xoops_loadLanguage('main', $DirName);
xoops_loadLanguage('commun', $DirName);
xoops_loadLanguage('admin',$DirName);

$$DirName->add_query($champs);
// Catégorie
$liste_cat = $$DirName->liste('categorie','`actif`=1');
$cid = $$DirName->requete('get','cid',$cid);

$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
if($cid > 0){
    if(!array_key_exists($cid,$liste_cat)){
        $_SESSION['cid'] = 0;
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/index.php', 3, _NOPERM);
        exit();
    }
    if (!$gperm_handler->checkRight($DirName.'_view', $cid, $groups, $xoopsModule->getVar('mid'))) {
        $_SESSION['cid'] = 0;
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/index.php', 3, _NOPERM);
        exit();
    }
    $_SESSION['cid'] = $cid;
}else{
    if(!isset($_GET['cid']) && isset($_SESSION['cid']) && array_key_exists($_SESSION['cid'],$liste_cat)) {
        $cid = $_SESSION['cid'];
    }else{
        $cid = 0;
        $_SESSION['cid'] = 0;
    }
}
