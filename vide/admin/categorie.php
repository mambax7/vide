<?php
$class = array('categorie','formulaire');
$champs = array(
    'id' => 'int',
    'pid' => 'int',
    'nom' => 'text',
    'created' => 'datetime',
    'descriptif' => 'text',
    'keywords' => 'text',
    'image' => 'file',
    'actif' => 'int'
);
$nom = '';
$pid = 0;
$keywords = '';
$descriptif = '';
$image = '';
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
    if ($pid != 0 && $$DirName->test_presence('categorie','id',$pid) === false){
        $tableau_erreur[] = constant('_AM_'.$moduleUP.'_PID_PASOK');
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
            $destname = $$DirName->createUploadName(URI_THUMB_CAT ,$fldname, true);
            $uploader = new XoopsMediaUploader(URI_THUMB_CAT, $allow_mimetype_cat, $xoopsModuleConfig[$DirName.'_maxfilesize']*1024);
            $uploader->setTargetFileName($destname);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if ($uploader->upload()) {

                        $$DirName->chg_resizePicture(URI_THUMB_CAT.'/'.$destname,URI_THUMB_CAT.'/redim_'.$destname, $xoopsModuleConfig[$DirName.'_width_thumb'], $xoopsModuleConfig[$DirName.'_height_thumb']);
                        $image = 'redim_'.$destname;

                } else {
                    $tableau_erreur[] = CHG_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                    $$DirName->inscrit_log('ERREUR_UPLOAD',$uploader->getErrors());
                    $image = 'blank.png';
                }
            } else {
                $$DirName->inscrit_log('ERREUR_UPLOAD',$uploader->getErrors());
                $tableau_erreur[] = CHG_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                $image = 'blank.png';
            }
        }
    }
    if (!is_file(URI_THUMB_CAT.'/'.$image)){
        $tableau_erreur[] = sprintf(CHG_PHOTO_INEX,$image);
        $image = 'blank.png';
    }
    if (count($tableau_erreur) > 0) {
        if ($id > 0){
            $op = 'modifier';
        } else {
            $op = 'ajouter';
        }
    } else {
        if ($id > 1) {
            if ($$DirName->modif_categorie($id, $pid, $nom, $descriptif, $keywords, $image, $actif) === false) {
                $$DirName->inscrit_log(
                    CHG_LOG_ERRSQL,
                    sprintf(CHG_LOG_ERR_MOD_SQL, $$DirName->liste_table['categorie'])
                );
                redirect_header(XOOPS_URL . '/modules/' . $DirName . '/admin/categorie.php', 5, CHG_ERR_INC, false);
                exit();
            }
        }else{
            if($$DirName->crea_categorie($pid,$nom, $descriptif, $keywords, $image, $actif) === false){
                $$DirName->inscrit_log(
                    CHG_LOG_ERRSQL,
                    sprintf(CHG_LOG_ERR_MOD_SQL, $$DirName->liste_table['categorie'])
                );
                redirect_header(XOOPS_URL . '/modules/' . $DirName . '/admin/categorie.php', 5, CHG_ERR_INC, false);
                exit();
            }
        }
        $message = ($id > 0) ? sprintf(CHG_LOG_UP_CAT,$nom) : sprintf(CHG_LOG_CREER_CAT,$nom);
        $$DirName->inscrit_log(CHG_LOG_OPSQL,$message);
    }
    $gperm_handler = &xoops_gethandler('groupperm');
    if($id > 0) {
        // Permissions
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('gperm_itemid', $id, '='));
        $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
        $criteria->add(new Criteria('gperm_name', $DirName . '_delete', '='));
        $gperm_handler->deleteAll($criteria);

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('gperm_itemid', $id, '='));
        $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
        $criteria->add(new Criteria('gperm_name', $DirName . '_submit', '='));
        $gperm_handler->deleteAll($criteria);

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('gperm_itemid', $id, '='));
        $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
        $criteria->add(new Criteria('gperm_name', $DirName . '_view', '='));
        $gperm_handler->deleteAll($criteria);
    }
    if(isset($_POST['groups_can_delete'])) {
        foreach($_POST['groups_can_delete'] as $onegroup_id) {
            $gperm_handler->addRight($DirName.'_delete', $id, $onegroup_id, $xoopsModule->getVar('mid'));
        }
    }
    if(isset($_POST['groups_can_submit'])) {
        foreach($_POST['groups_can_submit'] as $onegroup_id) {
            $gperm_handler->addRight($DirName.'_submit', $id, $onegroup_id, $xoopsModule->getVar('mid'));
        }
    }
    if(isset($_POST['groups_can_view'])) {
        foreach($_POST['groups_can_view'] as $onegroup_id) {
            $gperm_handler->addRight($DirName.'_view', $id, $onegroup_id, $xoopsModule->getVar('mid'));
        }
    }
    redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/categorie.php',5,CHG_OP_OK);
    exit();
}elseif($id > 0){
    $listing = $$DirName->liste('categorie','`id`='.$id);
    if (count($listing) == 0){
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/categorie.php', 5, CHG_NO_ID, FALSE);
        exit();
    }
    foreach($champs as $k=>$v){
        $$k = $listing[$id][$k];
    }
}elseif($op == 'ajouter'){
    foreach($champs as $k=>$v){
        $$k = $$DirName->defaut($v);
    }
}
switch($op){
    case'activer':
        if($$DirName->active_desactive($id,1,'categorie','id')){
            redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/categorie.php',5,CHG_OP_OK);
            exit();
        } else {
            redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
            exit();
        }

        break;
    case'desactiver':
        if($$DirName->active_desactive($id,0,'categorie','id')){
            redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/categorie.php',5,CHG_OP_OK);
            exit();
        } else {
            redirect_header(XOOPS_URL.'/admin.php', 5, CHG_ERR_INC, FALSE);
            exit();
        }
        break;
    case'supprimer':
        $content .= $$DirName->conf_sup($id,$listing[$id]['nom'],'/admin/categorie.php');
        break;
    case'conf_sup':
        if($$DirName->del_cat($id)){
            $$DirName->inscrit_log(CHG_LOG_OP,sprintf(CHG_LOG_DEL_SQL,$id,$liste_table['categorie']));
            redirect_header( XOOPS_URL.'/modules/' . $DirName .'/admin/categorie.php', 3, CHG_OP_OK);
            exit;
        }
        redirect_header(XOOPS_URL.'/modules/'.$DirName.'/admin/categorie.php', 5, CHG_ERR_INC, FALSE);
        exit();
        break;
    case'ajouter';
    case'modifier':

        $$DirName->add_admin_header('<script src="'.XOOPS_URL.'/modules/' . $DirName .'/lib_js/notifier.js" type="text/javascript"></script>');
        ob_start();
        $titre = ($op == 'ajouter') ? constant('_AM_'.$moduleUP.'_ADD_CAT') : constant('_AM_'.$moduleUP.'_MOD_CAT');
        $form = new XoopsThemeForm($titre, 'submitform', XOOPS_URL.'/modules/' . $DirName .'/admin/categorie.php');
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(CHG_NOM, 'nom', 50, 50, $nom), true);
        $soc = new XoopsFormSelect(constant('_AM_'.$moduleUP.'_PID'), 'pid', $pid);
        $soc->addOption(0, '- - - -');
        $liste_temp = $$DirName->liste_categorie();
        foreach ($liste_temp as $k=>$v) {
            $soc->addOption($k, $v);
        }
        $form->addElement($soc);
        unset($liste_temp,$soc);
        $form->addElement(new XoopsFormTextArea(constant('_AM_'.$moduleUP.'_CAT_DESC'), 'descriptif', $descriptif, 5, 50 ), true );
        $form->addElement(new XoopsFormTextArea(CHG_KEYWORDS, 'keywords', $keywords, 5, 50 ), true );
        $form->addElement(new XoopsFormRadioYN(CHG_ACTF, 'actif', $actif));
        $imgtray = new XoopsFormElementTray(sprintf(constant('_AM_'.$moduleUP.'_IMG'),CHG_DELA,mb_strtolower(constant('_AM_'.$moduleUP.'_CID'),'UTF-8')),'<br />');
        $imgpath = sprintf(constant('_AM_'.$moduleUP.'_LOCIMG'), URL_THUMB_CAT);
        $imageselect = new XoopsFormSelect($imgpath, 'image',$image);
        $topics_array = XoopsLists :: getImgListAsArray(URI_THUMB_CAT.'/');
        foreach( $topics_array as $image1 ) {
            $imageselect->addOption("$image1", $image1);
        }
        $imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"image\", \"" . 'uploads/'.$DirName.'/images/thumb_cat/' . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $imgtray->addElement($imageselect,false);
        $imgtray -> addElement( new XoopsFormLabel( '', '<br /><img src="'.URL_THUMB_CAT. '/'.$image.'" name="image3" id="image3" alt="" />' ) );
        $uploadfolder=sprintf(constant('_AM_'.$moduleUP.'_UPLOAD_WARNING'),URL_THUMB_CAT.'/');
        $fileseltray= new XoopsFormElementTray('','<br />');
        $fileseltray->addElement(new XoopsFormFile(constant('_AM_'.$moduleUP.'_TELECHARGE_IMG_CAT') , 'attachedfile', $xoopsModuleConfig[$DirName.'_maxfilesize']*1024), false);
        $fileseltray->addElement(new XoopsFormLabel($uploadfolder ), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        // Permissions
        $member_handler = & xoops_gethandler('member');
        $group_list = &$member_handler->getGroupList();
        $gperm_handler = &xoops_gethandler('groupperm');
        $full_list = array_keys($group_list);
        $groups_ids = array();
        if($id > 0) {        // Edit mode
            $groups_ids = $gperm_handler->getGroupIds($DirName.'_delete', $id, $xoopsModule->getVar('mid'));
            $groups_ids = array_values($groups_ids);
            $groups_can_delete_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_DELETEFORM'), 'groups_can_delete[]', $groups_ids);
        } else {    // Creation mode
            $groups_can_delete_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_DELETEFORM'), 'groups_can_delete[]', $full_list);
        }
        $groups_can_delete_checkbox->addOptionArray($group_list);
        $form->addElement($groups_can_delete_checkbox);
        $groups_ids = array();
        if($id > 0) {        // Edit mode
            $groups_ids = $gperm_handler->getGroupIds($DirName.'_submit', $id, $xoopsModule->getVar('mid'));
            $groups_ids = array_values($groups_ids);
            $groups_can_submit_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_SUBMITFORM'), 'groups_can_submit[]', $groups_ids);
        } else {    // Creation mode
            $groups_can_submit_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_SUBMITFORM'), 'groups_can_submit[]', $full_list);
        }
        $groups_can_submit_checkbox->addOptionArray($group_list);
        $form->addElement($groups_can_submit_checkbox);
        $groups_ids = array();
        if($id > 0) {        // Edit mode
            $groups_ids = $gperm_handler->getGroupIds($DirName.'_view', $id, $xoopsModule->getVar('mid'));
            $groups_ids = array_values($groups_ids);
            $groups_can_view_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_VIEWFORM'), 'groups_can_view[]', $groups_ids);
        } else {    // Creation mode
            $groups_can_view_checkbox = new XoopsFormCheckBox(constant('_AM_'.$moduleUP.'_VIEWFORM'), 'groups_can_view[]', $full_list);
        }
        $groups_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_can_view_checkbox);
        $form->addElement(new XoopsFormHidden('op', 'verif_form'));
        $form->addElement(new XoopsFormHidden('id', $id));
        $bouton_tray = new XoopsFormElementTray( '', '' );
        if ($id>0){
            $bouton_creer = new XoopsFormButton( '', '', $$DirName->modif(constant('_AM_'.$moduleUP.'_NOM_CAT')), 'submit' );
        }else {
            $bouton_creer = new XoopsFormButton( '', '', $$DirName->ajout(constant('_AM_'.$moduleUP.'_NOM_CAT')), 'submit' );
        }
        $bouton_tray->addElement( $bouton_creer );
        $form -> addElement( $bouton_tray );
        $form->display();
        $content .= ob_get_contents();
        ob_end_clean();
        break;
    default:
        $i = 0;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_NOM_CAT');
        $liste[$i]['champs'] = 'nom';
        $liste[$i]['class'] = 'w100 center';
        $liste[$i]['type'] = 'text';
        $i++;
        $liste[$i]['trad'] = constant('_AM_'.$moduleUP.'_PID');
        $liste[$i]['champs'] = 'pid';
        $liste[$i]['class'] = 'w200 center';
        $liste[$i]['type'] = 'list_cat';
        $i++;
        $liste[$i]['trad'] = CHG_IMG;
        $liste[$i]['champs'] = 'image';
        $liste[$i]['class'] = 'w150 center';
        $liste[$i]['type'] = 'img_cat';

        unset($i);
        $action = array(
            'modifier' => CHG_MODIF,
            'voir1' => CHG_VOIR,
            'supprimer' => CHG_SUP
        );
        $nb_log = $$DirName->compte('categorie','id');
        if ($nb_log > 0 && empty($op)){
            $$DirName->add_admin_header('<script src="'.XOOPS_URL.'/modules/' . $DirName .'/lib_js/popup.js" type="text/javascript"></script>');
            $listing = $$DirName->liste('categorie','','`pid` ASC','LIMIT '.(($xoopsModuleConfig[$DirName.'_nb_com'])*($page-1)).','.$xoopsModuleConfig[$DirName.'_nb_com']);
        } else {
            $listing = array();
        }
        $$DirName->entete_tableau(1,CHG_ADD,constant('_AM_'.$moduleUP.'_GCAT'));
        $$DirName->crea_tableau_admin($listing,'tableau_admin',$liste,$action,false,'id');
        $content .= $$DirName->aff_tableau();
        if ($nb_log > $xoopsModuleConfig[$DirName.'_nb_com']){
            $$DirName->num_page($nb_log,$page);
            $content .= $$DirName->pagenav;
        }
}
$mainAdmin = new ModuleAdmin();
echo $mainAdmin->addNavigation('categorie.php');
echo $content;
include 'admin_footer.php';
