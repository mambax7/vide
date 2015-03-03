<?php
$class = array('maintenance');
$champs = array();
include_once 'admin_header.php';
$liste_op = array(
    'optimise'  => constant('_AM_'.$moduleUP.'_OPTIMIZE'),
    'sauvebdd'  => constant('_AM_'.$moduleUP.'_SAUVEBDD'),
    'purge'    => constant('_AM_'.$moduleUP.'_SEELOG'),
);
if (!empty($op)){
    if( ! xoopsSecurity::checkReferer() ) {
        $$DirName->inscrit_log(CHG_LOG_SECU,CHG_LOG_HACK1);
        redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
        exit();
    }
}
switch($op){
    case'purge':
        $$DirName->purge_log_systeme();
        $$DirName->inscrit_log(CHG_LOG_MAINTENANCE,CHG_LOG_PURGELOG);
        redirect_header( XOOPS_URL.'/modules/' . $DirName .'/admin/maintenance.php', 3, CHG_OP_OK);
        exit();
        break;
    case'optimise':
        $$DirName->clean_bdd();
        $$DirName->inscrit_log(CHG_LOG_MAINTENANCE,CHG_LOG_OPTIMIZE);
        $$DirName->update_divers('optimise',time());
        redirect_header( XOOPS_URL.'/modules/' . $DirName .'/admin/maintenance.php', 3, CHG_OP_OK);
        exit;
        break;
    case'sauvebdd':
        $$DirName->dump_table($liste_table);
        $$DirName->resultat_table_bdd();
        if($$DirName->create_file_dump($uri_sauve_bdd) === false) {
            redirect_header(XOOPS_URL.'/modules/' . $DirName .'/admin/maintenance.php', 3, CHG_CREATE_FILE_ERROR);
            exit;
        }
        $$DirName->inscrit_log(CHG_LOG_MAINTENANCE,CHG_LOG_SAUVEBDD);
        $$DirName->update_divers('sauvebdd',time());
        $content .= '<div id="chg_maintenance"><p>'.constant('_AM_'.$moduleUP.'_ADMIN_FILE_BASESQL').'<br /><a href="'.str_replace(XOOPS_ROOT_PATH,XOOPS_URL,$$DirName->path_file).'">'.$$DirName->path_file.'</a></p>';
        $content .= '<p><button class="CHG_button" onClick="self.location.href=\''.XOOPS_URL.'/modules/' . $DirName . '/admin/maintenance.php\'">'.CHG_RETOUR.'</button></p></div>';
        break;
    default:
        ob_start();
        echo $$DirName->titre(constant('_AM_'.$moduleUP.'_MAINTENANCE'),'div');
        echo'<table id="tableau_admin"><tr>';
        echo'<th>'.constant('_AM_'.$moduleUP.'_ADMIN_OPMAINTENANCE').'</th><th class="w200">'.constant('_AM_'.$moduleUP.'_ACTION').'</th>';
        echo '</tr>';
        echo '<tbody>';
        if (count($liste_op) > 1){
            foreach($liste_op as $k=>$v){
                echo '<tr><td class="colg">'.$v.'</td><td>'.$$DirName->aff_button($k,constant('_AM_'.$moduleUP.'_LANCE'),'').'</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">'.CHG_NO_DONNEE.'</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        $content .= ob_get_contents();
        ob_end_clean();
        $nb_log = $$DirName->compte('log','id');
        if ($nb_log > 0 && empty($op)){
            $liste_log = $$DirName->liste('log','','`created` DESC','LIMIT '.(($xoopsModuleConfig[$DirName.'_nb_com'])*($page-1)).','.$xoopsModuleConfig[$DirName.'_nb_com']);
        } else {
            $liste_log = array();
        }
        $i = 0;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_TYPE_LOG');
        $liste[$i]['champs'] = 'titre';
        $liste[$i]['class'] = 'w80 center';
        $liste[$i]['type'] = 'string';
        $i++;
        $liste[$i]['trad'] = CHG_DATE;
        $liste[$i]['champs'] = 'created';
        $liste[$i]['class'] = 'w80 center';
        $liste[$i]['type'] = 'date';
        $i++;
        $liste[$i]['trad'] = CHG_USERID;
        $liste[$i]['champs'] = 'userid';
        $liste[$i]['class'] = 'w80 center';
        $liste[$i]['type'] = 'string';
        $i++;
        $liste[$i]['trad'] = CHG_DESC;
        $liste[$i]['champs'] = 'description';
        $liste[$i]['class'] = 'w250';
        $liste[$i]['type'] = 'string';
        $i++;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_IP');
        $liste[$i]['champs'] = 'ip';
        $liste[$i]['class'] = 'w80 center';
        $liste[$i]['type'] = 'string';
        unset($i);
        $action = array(
            'voir'=>CHG_VOIR
        );
        if ($nb_log > 0){
            $$moduleDirName->add_admin_header('<script src="'.XOOPS_URL.'/modules/' . $DirName .'/lib_js/popup.js" type="text/javascript"></script>');
        }
        $$DirName->inscrit_log(CHG_LOG_MAINTENANCE,CHG_LOG_VOIRLOG);
        $$DirName->entete_tableau(2,'Purge des logs',constant('_AM_'.$moduleUP.'_LOGSYS'));
        $$DirName->crea_tableau_admin($liste_log,'tableau_admin',$liste,$action,false,'id');
        $content .= $$DirName->aff_tableau();
        if ($nb_log > $xoopsModuleConfig[$DirName.'_nb_com']){
            $$DirName->num_page($nb_log,$page);
            $content .= $$DirName->pagenav;
        }
}
$mainAdmin = new ModuleAdmin();
echo $mainAdmin->addNavigation('maintenance.php');
echo $content;
include 'admin_footer.php';
