<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
if($$DirName->barenav == 1) echo ''.$$DirName->pagenav.'';
if (count($$DirName->fin_page)){
    echo implode("\n",$$DirName->fin_page);
}
if (is_array($tableau_erreur) && count($tableau_erreur) > 0) {
    $$DirName->add_erreur($tableau_erreur);
    $xoopsTpl->assign('footer', $$DirName->aff_mess_client());
}else{
    $xoopsTpl->assign('footer','');
}
include_once XOOPS_ROOT_PATH.'/footer.php';
