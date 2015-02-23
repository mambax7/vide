<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
if (!isset($DirName) OR $DirName != basename(dirname(dirname( __DIR__ )))){
    $DirName = basename(dirname(dirname(__DIR__)));
}
if (!isset($moduleUP)) $moduleUP = strtoupper($DirName);

// Maintenance
define('_AM_'.$moduleUP.'_OPTIMIZE','Nettoyage et optimisation de la BDD');
define('_AM_'.$moduleUP.'_SAUVEBDD','Sauvegarde de la BDD du module');
define('_AM_'.$moduleUP.'_SEELOG','Voir et purger les logs du modules');
define('_AM_'.$moduleUP.'_ADMIN_FILE_BASESQL', 'Vous pouvez télécharger le fichier de sauvegarde ici :');
define('_AM_'.$moduleUP.'_ADMIN_OPMAINTENANCE','Opération(s) de maintenance disponible(s)');
define('_AM_'.$moduleUP.'_ACTION','Action(s)');
define('_AM_'.$moduleUP.'_LANCE','Lancer l\'opération');
define('_AM_'.$moduleUP.'_IP','Adresse IP');
define('_AM_'.$moduleUP.'_TYPE_LOG','Type de log');
define('_AM_'.$moduleUP.'_LOGSYS','Logs système');
define('_AM_'.$moduleUP.'_URL','URL de la page');
define('_AM_'.$moduleUP.'_REFFERER','En provenance de');
define('_AM_'.$moduleUP.'_MAINTENANCE','Maintenance');
// Accueil
define('_AM_'.$moduleUP.'_STAT','Statistiques');
define('_AM_'.$moduleUP.'_REQUIS','Nécessaires requis');
//define('_AM_'.$moduleUP.'_TCPDF','Frameworks TCPDF_for_xoops version 1.06 minimum : %s');
define('_AM_'.$moduleUP.'_WIDEIMAGE', 'Frameworks WideImage_for_xoops version 1.00 minimum : %s');
define('_AM_'.$moduleUP.'_GD2', 'Librairie PHP GD2 : %s');
define('_AM_'.$moduleUP.'_TODOLIST','Tâche(s) a effectuer');
define('_AM_'.$moduleUP.'_PASOPT','Aucune optimisation de la BDD effectuée');
define('_AM_'.$moduleUP.'_PASSAUVE','Aucune sauvegarde de la BDD effectuée');
define('_AM_'.$moduleUP.'_OPT_AFAIRE','Optimisation de la BDD a prévoir :');
define('_AM_'.$moduleUP.'_OPT_FAIT','Aucune optimisation a prévoir :');
define('_AM_'.$moduleUP.'_SAUVE_AFAIRE','Sauvegarde de la BDD a prévoir :');
define('_AM_'.$moduleUP.'_SAUVE_FAIT','Aucune sauvegarde a prévoir :');
//Categories
define('_AM_'.$moduleUP.'_NOM_CAT','Catégorie');
define('_AM_'.$moduleUP.'_PID','Catégorie parent');
define('_AM_'.$moduleUP.'_GCAT','Gestion des catégories');
define('_AM_'.$moduleUP.'_ADD_CAT','Ajouter une catégorie');
define('_AM_'.$moduleUP.'_MOD_CAT','Modifier une catégorie');
define('_AM_'.$moduleUP.'_CAT_DESC','Description');
define('_AM_'.$moduleUP.'_TELECHARGE_IMG_CAT', 'Télécharger l\'image de la catégorie');
define('_AM_'.$moduleUP.'_DELETEFORM','Groupe autorisé à supprimer un fichier dans cette catégorie');
define('_AM_'.$moduleUP.'_SUBMITFORM','Groupe autorisé à soumettre un fichier dans cette catégorie');
define('_AM_'.$moduleUP.'_VIEWFORM','Groupe autorisé à consulter les fichiers de cette catégorie');
define('_AM_'.$moduleUP.'_PID_PASOK','Mauvaise catégorie');
define('_AM_'.$moduleUP.'_CAT_PRINC','Catégorie principale');
define('_AM_'.$moduleUP.'_CAT_INC','Catégorie inconnue');
// Item
define('_AM_'.$moduleUP.'_NOM_ITEM','Titre');
define('_AM_'.$moduleUP.'_CID','Catégorie');
define('_AM_'.$moduleUP.'_GITEM','Gestion des objets');
define('_AM_'.$moduleUP.'_ADD_ITEM','Ajouter un fichier');
define('_AM_'.$moduleUP.'_MOD_ITEM','Modifier un fichier');
define('_AM_'.$moduleUP.'_ITEM_DESC','Description');
define('_AM_'.$moduleUP.'_UPLOAD_FILE','Document à télécharger');
define('_AM_'.$moduleUP.'_PDF','Fichier PDF seulement');
define('_AM_'.$moduleUP.'_CID_PASOK','Mauvaise catégorie');
define('_AM_'.$moduleUP.'_NOM_FIC','Nom du fichier');
// Images
define('_AM_'.$moduleUP.'_IMG','Image %s %s');
define('_AM_'.$moduleUP.'_LOCIMG','Répertoire de sauvegarde : %s');
define('_AM_'.$moduleUP.'_UPLOAD_WARNING','<strong>Attention, n\'oubliez pas d\'appliquer les permissions d\'écriture au répertoire suivant : %s </strong>');
