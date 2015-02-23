<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
// Fonction créateur de fonction (permet le clonage)
if (!function_exists('chg_parse_function')){
    function chg_parse_function($function_string, $pattern='', $replacement=''){
        global $moduleDirName,$xoopsDB,$liste_table;
        if(empty($function_string)) return ;
        if (empty($DirName)) $DirName = basename(dirname(__DIR__));
        require_once XOOPS_ROOT_PATH.'/modules/'.$DirName.'/include/config.php';
        $patterns = array("/\[DIRNAME\]/", "/\[VAR_PREFIX\]/");
        $replacements = array($DirName, $DirName);
        if(!empty($pattern) && !is_array($pattern) && !is_array($replacement)){
            $pattern = array($pattern);
            $replacement = array($replacement);
        }
        if(is_array($pattern) && count($pattern)>0){
            $ii = 0;
            foreach($pattern as $pat){
                if(!in_array($pat, $patterns)){
                    $patterns[] = $pat;
                    $replacements[] = isset($replacement[$ii])?$replacement[$ii]:"";
                }
                $ii++;
            }
        }
        $function_string = preg_replace($patterns, $replacements, $function_string);
        eval($function_string);
        return true;
    }
}



 