<?php
$class = array('admin');
$champs = array(
    'cid' => 'int',
);
$cid = 0;
$liste_cat = array();
include_once 'header.php';

$xoopsOption['template_main'] =  $DirName.'_index.html';
include_once XOOPS_ROOT_PATH.'/header.php';
// Affichage des catégories
$liste_temp = array();
foreach($liste_cat as $k=>$v){
    if($cid == $v['pid'] && $gperm_handler->checkRight($DirName.'_view', $v['id'], $groups, $xoopsModule->getVar('mid'))) {
        if(count($liste_temp) == 0 && $v['pid'] > 0) $liste_temp[] = '<li><a href="?cid='.$v['pid'].'" title="RETOUR"><img src="'.XOOPS_URL .'/modules/'.$DirName.'/images/retour.png" alt="RETOUR"/></a></li>';
        $liste_temp[] = '<li><a href="?cid='.$k.'" title="'.stripslashes($v['nom']) .'"><img src="'.URL_THUMB_CAT.'/'.$v['image'].'" alt="'.stripslashes($v['nom']) .'" /></a></li>';
    }
}
if(count($liste_temp) == 0 && $cid > 0) $liste_temp[] = '<li><a href="?cid=0" title="RETOUR"><img src="'.XOOPS_URL .'/modules/'.$DirName.'/images/retour.png" alt="RETOUR"/></a></li>';
unset($k,$v);
$xoTheme->addStyleSheet('modules/'.$DirName.'/css/style.css');
$bloc_cat = '<div class="bloc_haut"><h2>'.constant('_MA_'.$moduleUP.'_CAT') .'</h2>';
if(count($liste_temp) > 0){
    $bloc_cat .= '<ul class="bloc_cat">'.implode('',$liste_temp).'</ul>';
}else{
    $bloc_cat .= CHG_NO_DONNEE;
}
$bloc_cat .= '</div>';
$liste_temp = array();
$tableau = '';
$liste_file = $$DirName->liste('item','`actif`=1 AND `cid`='.$cid,'`created` DESC');
foreach($liste_file as $k=>$v){
    $liste_temp[] = '<li><a href="voir.php?id='.$k.'" title="'.stripslashes($v['nom']) .'">'.stripslashes($v['nom']) .'</a></li>';
}
$tableau = '<div class="bloc_bas"><h2>'.constant('_MA_'.$moduleUP.'_FILE') .'</h2><p>'.constant('_AM_'.$moduleUP.'_NOM_CAT').' : '. $$DirName->cellule('list_cat',$cid).'</p>';
if(count($liste_temp) > 0){
    $tableau .= '<ul class="bloc_file">'.implode('',$liste_temp).'</ul>';
}else{
    $tableau .= CHG_NO_DONNEE;
}
$tableau .= '</div>';
unset($liste_temp);
$xoopsTpl->assign('xoops_pagetitle', $xoopsModuleConfig[$DirName.'_title']);
$xoTheme->addMeta( 'meta', 'keywords', $xoopsModuleConfig[$DirName.'_keywords']);
$xoTheme->addMeta( 'meta', 'description', $xoopsModuleConfig[$DirName.'_description']);
$xoopsTpl->assign('bloc_cat',$bloc_cat);
$xoopsTpl->assign('tableau',$tableau);
$xoopsTpl->assign('le_titre',constant('_MA_'.$moduleUP.'_TITRE_INDEX'));
include_once 'footer.php';