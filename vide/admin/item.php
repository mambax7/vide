<?php
$class = array('item','categorie','formulaire');
$champs = array(
    'id' => 'int',
    'cid' => 'int',
    'nom' => 'text',
    'created' => 'datetime',
    'descriptif' => 'text',
    'keywords' => 'text',
    'fichier' => 'string',
    'actif' => 'int'
);
$nom = '';
$cid = 0;
$keywords = '';
$descriptif = '';
$fichier = '';
$actif = 1;
include_once 'admin_header.php';
$$DirName->add_query($champs);
if (!empty($op)){
    if( ! xoopsSecurity::checkReferer() ) {
        $$DirName->inscrit_log(CHG_LOG_SECU,CHG_LOG_HACK1);
        redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
        exit();
    }
}
if($op == 'verif_form') {
    foreach ($champs as $k => $v) {
        if (!isset($$k)) {
            $$k = $$DirName->requete('post', $k, $$DirName->defaut($v));
        } else {
            $$k = $$DirName->requete('post', $k, $$k);
        }
    }
    if ($$DirName->min_max($nom,3,50) === false){
        $tableau_erreur[] = sprintf(constant('_AM_'.$moduleUP.'_MIN_MAX_PAS_OK'),CHG_NOM,3,50).CHG_CARACTERES;
    }
    if ($cid != 0 && $$DirName->test_presence('categorie','id',$cid) === false){
        $tableau_erreur[] = constant('_AM_'.$moduleUP.'_CID_PASOK');
    }
    if (empty($descriptif)){
        $tableau_erreur[] = sprintf(CHG_CHAMPS_VIDE,CHG_DESC);
    }
    if (empty($keywords)){
        $tableau_erreur[] = sprintf(CHG_CHAMPS_VIDE,CHG_KEYWORDS);
    }
    if($$DirName->onoff($actif) === false){
        $tableau_erreur[] = sprintf(CHG_ONOFF_NOTOK,CHG_ACTF);
    }
    if(isset($_POST['xoops_upload_file'])) {
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
        $fldname = $fldname['name'];
        if(xoops_trim($fldname!='')) {
            $destname = $$DirName->createUploadName(URI_ITEM ,$fldname, true);
            $uploader = new XoopsMediaUploader(URI_ITEM, $allow_mimetype_item, $xoopsModuleConfig[$DirName.'_maxfilesize']*1024);
            $uploader->extensionToMime = array_merge($uploader->extensionToMime,array('pdf'=>'application/pdf'));
            $uploader->setTargetFileName($destname);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if ($uploader->upload()) {
                    $fichier = $destname;

                } else {
                    $tableau_erreur[] = CHG_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                    $$DirName->inscrit_log('ERREUR_UPLOAD',$uploader->getErrors());
                    $fichier = '';
                }
            } else {
                $$DirName->inscrit_log('ERREUR_UPLOAD',$uploader->getErrors());
                $tableau_erreur[] = CHG_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                $fichier = '';
            }
        }
    }
    if (!is_file(URI_ITEM.'/'.$fichier)){
        $tableau_erreur[] = sprintf(CHG_PHOTO_INEX,$fichier);
        $fichier = '';
    }
    if (count($tableau_erreur) > 0) {
        if ($id > 0){
            $op = 'modifier';
        } else {
            $op = 'ajouter';
        }
    } else {
        if ($id > 1) {
            if ($$DirName->modif_item($id, $cid, $nom, $descriptif, $keywords, $fichier, $actif) === false) {
                $$DirName->inscrit_log(
                    CHG_LOG_ERRSQL,
                    sprintf(CHG_LOG_ERR_MOD_SQL, $$DirName->liste_table['item'])
                );
                redirect_header(XOOPS_URL . '/modules/' . $DirName . '/admin/item.php', 5, CHG_ERR_INC, false);
                exit();
            }
        }else{
            if($$DirName->crea_item($cid,$nom, $descriptif, $keywords, $fichier, $actif) === false){
                $$DirName->inscrit_log(
                    CHG_LOG_ERRSQL,
                    sprintf(CHG_LOG_ERR_MOD_SQL, $$DirName->liste_table['item'])
                );
                redirect_header(XOOPS_URL . '/modules/' . $DirName . '/admin/item.php', 5, CHG_ERR_INC, false);
                exit();
            }
        }
        $message = ($id > 0) ? sprintf(CHG_LOG_UP_ITEM,$nom) : sprintf(CHG_LOG_CREER_ITEM,$nom);
        $$DirName->inscrit_log(CHG_LOG_OPSQL,$message);
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/item.php',5,CHG_OP_OK);
        exit();
    }
}
switch($op){
    case'activer':
        if($$DirName->active_desactive($id,1,'item','id')){
            redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/item.php',5,CHG_OP_OK);
            exit();
        } else {
            redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
            exit();
        }

        break;
    case'desactiver':
        if($$DirName->active_desactive($id,0,'item','id')){
            redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/item.php',5,CHG_OP_OK);
            exit();
        } else {
            redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
            exit();
        }
        break;
    case'sup':
        $content .= $$DirName->conf_sup($id,$listing[$id]['nom'],'/admin/item.php');
        break;
    case'conf_sup':
        if($$DirName->del_item($id)){
            $$DirName->inscrit_log(CHG_LOG_OP,sprintf(CHG_LOG_DEL_SQL,$id,$liste_table['item']));
            redirect_header( XOOPS_URL.'/modules/' . $DirName .'/admin/item.php', 3, CHG_OP_OK);
            exit;
        }
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/item.php', 5, CHG_ERR_INC, FALSE);
        exit();
        break;
    case'ajouter';
    case'modifier':

        $$DirName->add_admin_header('<script src="'.XOOPS_URL.'/modules/' . $DirName .'/lib_js/notifier.js" type="text/javascript"></script>');
        ob_start();
        $titre = ($op == 'ajouter') ? constant('_AM_'.$moduleUP.'_ADD_ITEM') : constant('_AM_'.$moduleUP.'_MOD_ITEM');
        $form = new XoopsThemeForm($titre, 'submitform', XOOPS_URL.'/modules/' . $DirName .'/admin/item.php');
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(CHG_NOM, 'nom', 50, 50, $nom), true);
        $soc = new XoopsFormSelect(constant('_AM_'.$moduleUP.'_NOM_CAT'), 'cid', $cid);
        $soc->addOption(0, '- - - -');
        $liste_temp = $$DirName->liste_categorie();
        foreach ($liste_temp as $k=>$v) {
            $soc->addOption($k, $v);
        }
        $form->addElement($soc);
        unset($liste_temp,$soc);
        $form->addElement(new XoopsFormTextArea(constant('_AM_'.$moduleUP.'_ITEM_DESC'), 'descriptif', $descriptif, 5, 50 ), true );
        $form->addElement(new XoopsFormTextArea(CHG_KEYWORDS, 'keywords', $keywords, 5, 50 ), true );
        $form->addElement(new XoopsFormRadioYN(CHG_ACTF, 'actif', $actif));
        $file = new XoopsFormElementTray(constant('_AM_'.$moduleUP.'_UPLOAD_FILE'),'<br />');
        $file->addElement(new XoopsFormFile(constant('_AM_'.$moduleUP.'_PDF') , 'attachedfile', $xoopsModuleConfig[$DirName.'_maxfilesize']*1024), true);
        $file->addElement(new XoopsFormLabel('<br />'.URL_ITEM.'/' ), false);
        $form->addElement($file);

        $form->addElement(new XoopsFormHidden('op', 'verif_form'));
        $form->addElement(new XoopsFormHidden('id', $id));
        $bouton_tray = new XoopsFormElementTray( '', '' );
        if ($id>0){
            $bouton_creer = new XoopsFormButton( '', '', $$DirName->modif(constant('_AM_'.$moduleUP.'_NOM_ITEM')), 'submit' );
        }else {
            $bouton_creer = new XoopsFormButton( '', '', $$DirName->ajout(constant('_AM_'.$moduleUP.'_NOM_ITEM')), 'submit' );
        }
        $bouton_tray->addElement( $bouton_creer );
        $form -> addElement( $bouton_tray );
        $form->display();
        $content .= ob_get_contents();
        ob_end_clean();
        break;
    default:
        $i = 0;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_NOM_ITEM');
        $liste[$i]['champs'] = 'nom';
        $liste[$i]['class'] = 'w100 center';
        $liste[$i]['type'] = 'text';
        $i++;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_CID');
        $liste[$i]['champs'] = 'cid';
        $liste[$i]['class'] = 'w200 center';
        $liste[$i]['type'] = 'list_cat';
        $i++;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_NOM_FIC');
        $liste[$i]['champs'] = 'fichier';
        $liste[$i]['class'] = 'w150 center';
        $liste[$i]['type'] = 'string';

        unset($i);
        $action = array(
            'modifier' => CHG_MODIF,
            'voir2' => CHG_VOIR,
            'supprimer' => CHG_SUP
        );
        $nb_log = $$DirName->compte('item','id');
        if ($nb_log > 0 && empty($op)){
            $$DirName->add_admin_header('<script src="'.XOOPS_URL.'/modules/' . $DirName .'/lib_js/popup.js" type="text/javascript"></script>');
            $listing = $$DirName->liste('item','','`created` DESC','LIMIT '.(($xoopsModuleConfig[$DirName.'_nb_com'])*($page-1)).','.$xoopsModuleConfig[$DirName.'_nb_com']);
        } else {
            $listing = array();
        }
        $$DirName->entete_tableau(1,CHG_ADD,constant('_AM_'.$moduleUP.'_GITEM'));
        $$DirName->crea_tableau_admin($listing,'tableau_admin',$liste,$action,false,'id');
        $content .= $$DirName->aff_tableau();
        if ($nb_log > $xoopsModuleConfig[$DirName.'_nb_com']){
            $$DirName->num_page($nb_log,$page);
            $content .= $$DirName->pagenav;
        }
}
$mainAdmin = new ModuleAdmin();
echo $mainAdmin->addNavigation('item.php');
echo $content;
include 'admin_footer.php';