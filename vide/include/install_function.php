<?php
$moduleDirName =  basename(dirname(dirname( __FILE__ )));
require_once XOOPS_ROOT_PATH.'/modules/'.$moduleDirName.'/include/'.'function.special.php';
chg_parse_function('
function xoops_module_pre_install_CHG_miniboutique(&$xoopsModule) {
    $minSupportedVersion = explode(\'.\', \'2.5.0\');
    $currentVersion = explode(\'.\', substr(XOOPS_VERSION,6));
    if($currentVersion[0] > $minSupportedVersion[0]) {
        return true;
    } elseif($currentVersion[0] == $minSupportedVersion[0]) {
        if($currentVersion[1] > $minSupportedVersion[1]) {
            return true;
        } elseif($currentVersion[1] == $minSupportedVersion[1]) {
            if($currentVersion[2] > $minSupportedVersion[2]) {
                return true;
            } elseif ($currentVersion[2] == $minSupportedVersion[2]) {
                return true;
            }
        }
    }
    return false;
}
function xoops_module_pre_install_[DIRNAME] (&$xoopsModule){
    $indexFile = XOOPS_ROOT_PATH.\'/modules/\'.$xoopsModule->getVar(\'dirname\').\'/include/index.html\';
    if(!is_dir(XOOPS_ROOT_PATH.\'/uploads/sauve\')){
        if (!mkdir(XOOPS_ROOT_PATH.\'/uploads/sauve\',0777)) return false;
        if (!copy($indexFile, XOOPS_ROOT_PATH.\'/uploads/sauve/index.html\')) return false;
    }
	if(!is_dir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]\')){
        if (!mkdir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]\',0777)) return false;
        if (!copy($indexFile, XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/index.html\')) return false;
    }
    if(!is_dir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/item\')){
        if (!mkdir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/item\',0777)) return false;
        if (!copy($indexFile, XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/item/index.html\')) return false;
    }
    if(!is_dir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/thumb_cat\')){
    if (!mkdir(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/thumb_cat\',0777)) return false;
        if (!copy($indexFile, XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/thumb_cat/index.html\')) return false;
    }
    if(!is_file(XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/thumb_cat/blank.png\')){
        copy(XOOPS_ROOT_PATH.\'/modules/[DIRNAME]/images/blank.png\',XOOPS_ROOT_PATH.\'/uploads/[DIRNAME]/thumb_cat/blank.png\');
    }
    return true;
}');
