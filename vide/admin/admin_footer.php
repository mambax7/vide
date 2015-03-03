<?php

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
echo '<div class="adminfooter"><div style="text-align: center;"><a href="http://www.chg-web.org" rel="external"><img src="'.$pathIcon32.'/xoopsmicrobutton.gif" alt="Création de module pour Xoops" title="Création de module pour Xoops"></a></div>';
echo '<div class="center smallsmall italic pad5">'.CHG_FOOT_ADMIN.'</div></div>';
if (count($$DirName->fin_page)){
    echo implode("\n",$$DirName->fin_page);
}
if (is_array($tableau_erreur) && count($tableau_erreur) > 0) {
    $$DirName->add_erreur($tableau_erreur);
    echo $$DirName->aff_mess_client();
}
$xoopsTpl->assign('xoops_module_header',$$DirName->aff_admin_header());
xoops_cp_footer();
