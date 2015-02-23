<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
// TODO : // A adapter à la BDD

include_once __DIR__.'/config.php';
include_once __DIR__.'/function.special.php';
chg_parse_function('
    function &[VAR_PREFIX]_search($DirName,$queryarray, $andor, $limit, $offset,$userid=0){
	    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;
        $ret = array();
        gperm_handler =& xoops_gethandler(\'groupperm\');
	    if (is_object($xoopsUser)) {
	        $groups = $xoopsUser->getGroups();
	    } else {
		    $groups = XOOPS_GROUP_ANONYMOUS;
	    }
	    $sql = "SELECT * FROM ".$xoopsDB->prefix($liste_table[\'item\'])." ORDER BY created DESC WHERE actif=1 ";
	    if ($userid != 0) $sql .= "AND user_id=".$userid." ";
	    $count = count($queryarray);
	    if ( $count > 0 && is_array($queryarray) ) {
		    $sql .= "AND ((nom LIKE \'%$queryarray[0]%\' OR descriptif LIKE \'%$queryarray[0]%\')";
		    for ( $i = 1; $i < $count; $i++ ) {
			    $sql .= " $andor ";
			    $sql .= "(nom LIKE \'%$queryarray[$i]%\' OR descriptif LIKE \'%$queryarray[$i]%\')";
		    }
		    $sql .= ") ";
	    }

	    $result = $xoopsDB->query($sql,$limit,$offset);
	    $ret = array();
	    $i = 0;
	    while ( $myrow = $xoopsDB->fetchArray($result) ) {
		    $ret[$i][\'image\'] = "images/".$DirName.".png";
		    $ret[$i][\'link\'] = "details.php?blog_id=".$myrow[\'id\'];
		    $ret[$i][\'title\'] = $myrow[\'title\'];
		    $ret[$i][\'time\'] = $myrow[\'created\'];
		    $ret[$i][\'uid\'] = $myrow[\'user_id\'];
		    if( !empty( $myrow[\'contents\'] ) ){
			    $context = preg_replace("/-{3}(\w+)-{3}/","",strip_tags($myrow[\'contents\']));
			    $context = strip_tags($myts->displayTarea($context,1,0,1,0,1));
			    $ret[$i][\'context\'] = search_make_context($context,$queryarray);
		    }
		    $i++;
	    }
	    return $ret;
    }
');
 