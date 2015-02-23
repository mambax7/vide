<?php
/**
 * CHG-WEB - MONTUY337513 - Philodenelle
 * 
 */
// Fichier MySQL - V1.00
// CHG-WEB - montuy337513 (cm@chg-web.com) - Philodenelle (philodenelle@chg-web.com)
header("Content-Type: text/mysql");
$liste_table = array();
$requete = array();
$content = '';
$type_text = array('datetime','time','date','timestamp','year');
require(dirname(__DIR__).'/include/config.php');
$table[$DirName.'_log'] = array(
    array (
        'champs' => 'id',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'auto_increment|unsigned'
    ),
    array (
        'champs' => 'titre',
        'type' => 'varchar(100)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'created',
        'type' => 'datetime',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'description',
        'type' => 'varchar(200)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'userid',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'ip',
        'type' => 'varchar(50)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
	array (
        'champs' => 'url',
        'type' => 'varchar(150)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
	array (
        'champs' => 'provenance',
        'type' => 'varchar(150)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    'primary' => 'id',
//	'key' => 'user_id|user_id,created,title,private', == KEY user_id (user_id,created,title,private)
    'engine' => 'MyISAM'
);
$table[$DirName.'_divers'] = array(
    array (
        'champs' => 'id',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'auto_increment|unsigned'
    ),
    array (
        'champs' => 'nom',
        'type' => 'varchar(25)',
        'null' => 0,
        'default' => '0',
        'divers' => ''
    ),
	array (
        'champs' => 'valeur',
        'type' => 'varchar(150)',
        'null' => 0,
        'default' => '0',
        'divers' => ''
    ),
    'primary' => 'id',
    'engine' => 'MyISAM'
);
$table[$DirName.'_categorie'] = array(
    array (
        'champs' => 'id',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'auto_increment|unsigned'
    ),
    array (
        'champs' => 'pid',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'unsigned'
    ),
    array (
        'champs' => 'nom',
        'type' => 'varchar(200)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'created',
        'type' => 'datetime',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'descriptif',
        'type' => 'text',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'keywords',
        'type' => 'text',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'image',
        'type' => 'varchar(200)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'actif',
        'type' => 'tinyint(1)',
        'null' => 0,
        'default' => '1',
        'divers' => ''
    ),
    'primary' => 'id',
    'engine' => 'MyISAM'
);
$table[$DirName.'_item'] = array(
    array (
        'champs' => 'id',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'auto_increment|unsigned'
    ),
    array (
        'champs' => 'cid',
        'type' => 'int(11)',
        'null' => 0,
        'default' => '',
        'divers' => 'unsigned'
    ),
    array (
        'champs' => 'nom',
        'type' => 'varchar(200)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'created',
        'type' => 'datetime',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'descriptif',
        'type' => 'text',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'keywords',
        'type' => 'text',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),

    array (
        'champs' => 'fichier',
        'type' => 'varchar(200)',
        'null' => 0,
        'default' => '',
        'divers' => ''
    ),
    array (
        'champs' => 'actif',
        'type' => 'tinyint(1)',
        'null' => 0,
        'default' => '1',
        'divers' => ''
    ),
    'primary' => 'id',
    'engine' => 'MyISAM'
);
// A compléter
$engine = '';
define('_EN_TETE','#'."\n".'# Table structure for table %s'."\n".'#'."\n");
define('_STR_INSERT','CREATE TABLE `%s` ('."\n".'%s'."\n".')%s;'."\n");
define('_ENGINE', 'ENGINE=%s DEFAULT CHARSET=utf8');
foreach($liste_table as $k=>$v){
    if (array_key_exists($v,$table)){
        if(is_array($table[$v])){
            $content .= sprintf(_EN_TETE,$v);
            foreach ($table[$v] as $kk=>$vv){
                if(is_array($vv)){
                    $divers = explode('|',$vv['divers']);
                    $colonne = '`'.$vv['champs'].'` ';
                    $type = $vv['type'];
                    if(in_array('unsigned',$divers) or in_array('UNSIGNED',$divers)) $type = $type.' unsigned ';
                    $temp_type = explode('(',$type);
                    $ttype = (count($temp_type) == 0) ? strtolower($type) : strtolower($temp_type[0]);
                    unset($temp_type);
                    $nul = ($vv['null'] == 1) ? '' : ' NOT NULL ';
                    if($vv['default'] == '' && in_array($ttype,$type_text)) {
                        $defaut = '';
                    }else{
                        $defaut = ' default \''.$vv['default'].'\' ';
                    }
                    $increment = (in_array('auto_increment',$divers) or in_array('AUTO_INCREMENT',$divers)) ? ' auto_increment ' : '';
                    $requete[] = $colonne.$type.$nul.$defaut.$increment;


                }
                if(!is_array($vv) && $kk == 'primary') $requete[] = 'PRIMARY KEY (`'.$vv.'`)';
                if(!is_array($vv) && $kk == 'key' && !empty($vv)){
                    $t = explode('|',$vv);
                    $tt = '';
                    if (count($t) > 0){
                        $tt = 'KEY `'.$t[0].'` ';
                        if (count($t) == 2){
                            $ttt = explode(',',$t[1]);
                            $tt .= '(`'.implode('`,`',$ttt).'`)';
                            unset($ttt);
                        }
                    }
                    $requete[] = $tt;
                }

                unset($tt,$t);
                if(!is_array($vv) && $kk == 'engine') $engine = sprintf(_ENGINE,$vv);
            }
            $content .= sprintf(_STR_INSERT,$v,implode(','."\n",$requete),$engine);
            $requete = array();
        }

    }
}
echo $content; 