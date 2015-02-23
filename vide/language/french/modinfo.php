<?php
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
if (!isset($DirName) OR $DirName != basename(dirname(dirname(__DIR__ )))){
    $DirName = basename(dirname(dirname(__DIR__)));

}
if (!isset($moduleUP)) $moduleUP = strtoupper($DirName);
define('_MI_'.$moduleUP.'_NAME','Module Vide');
define('_MI_'.$moduleUP.'_DESC','Ceci est un module vide');
define('_MI_'.$moduleUP.'_VERSION','1.00');
define('_MI_'.$moduleUP.'_LICENCE','GNU GPL 3.0 or later');
define('_MI_'.$moduleUP.'_LICENCE_URL','https://www.gnu.org/licenses/gpl.html');
define('_MI_'.$moduleUP.'_MODULE_WEB_URL','http://www.chg-web.org/index.php');
define('_MI_'.$moduleUP.'_MODULE_WEB_NAME','chg-web.org');
define('_MI_'.$moduleUP.'_AUTHOR_URL','http://www.chg-web.com');
define('_MI_'.$moduleUP.'_AUTHOR_NAME','chg-web.com par montuy337513 et Philodenelle');
define('_MI_'.$moduleUP.'_SUBMIT_BUG','http://www.chg-web.org');
define('_MI_'.$moduleUP.'_SUBMIT_FEATURE','http://www.chg-web.org');
define('_MI_'.$moduleUP.'_TITRE','Titre de la page par défaut');
define('_MI_'.$moduleUP.'_MOTCLE','Meta_keywords par défaut');
define('_MI_'.$moduleUP.'_DESCRIPTION','Meta_description par défaut');
define('_MI_'.$moduleUP.'_CREDIT','montuy337513 et Philodenelle');
define('_MI_'.$moduleUP.'_DATE_RELEASE', '2014/10/04');
define('_MI_'.$moduleUP.'_STATUS','Beta 1');
define('_MI_'.$moduleUP.'_MODULE_DEMO_URL','');
define('_MI_'.$moduleUP.'_MODULE_DEMO_NAME','');
define('_MI_'.$moduleUP.'_DEV','Montuy337513, Philodenelle');
define('_MI_'.$moduleUP.'_TRANSLATE','Montuy337513, Philodenelle');
define('_MI_'.$moduleUP.'_TEST','Montuy337513, Philodenelle');
define('_MI_'.$moduleUP.'_DOCU','Montuy337513, Philodenelle');
// Blocs
define('_MI_'.$moduleUP.'_BNAME1','Bloc 1');
define('_MI_'.$moduleUP.'_BNAME1_DESC','Description Bloc 1');

// Templates

// Menu admin

// Menu coté client
define('_MI_'.$moduleUP.'_MENU_SEARCH','Recherche avancée');
define('_MI_'.$moduleUP.'_MENU_ADD','Ajouter un fichier');
// Menu
define('_MI_'.$moduleUP.'_MENU_CAT','Catégories');
define('_MI_'.$moduleUP.'_MENU_ITEM','Articles');
// Préférences
define('_MI_'.$moduleUP.'_LCEN','Compatibilité LCEN');
define('_MI_'.$moduleUP.'_LCEN_DSC','Mettre sur oui si vous êtes en France. Rend le module compatible avec la loi LCEN.');
define('_MI_'.$moduleUP.'_TODO','Activation de la TODO LIST');
define('_MI_'.$moduleUP.'_TODO_DSC','Voulez-vous activer la fonctionnalité "TODO LIST" ?');
define('_MI_'.$moduleUP.'_COMINDEX','Longueur de la liste sur la page d\'accueil');
define('_MI_'.$moduleUP.'_COMINDEX_DSC','Préciser la longueur du tableau sur la page d\'accueil.');
define('_MI_'.$moduleUP.'_TITLE','Titre par défaut');
define('_MI_'.$moduleUP.'_TITLE_DSC','Indiquer le titre de la page que vous désirez.');
define('_MI_'.$moduleUP.'_KEYWORDS','META_KEYWORDS');
define('_MI_'.$moduleUP.'_KEYWORDS_DSC','Indiquer les meta-keywords par défaut (séparés par une virgule).');
define('_MI_'.$moduleUP.'_METADESCRIPTION','META_DESCRIPTION');
define('_MI_'.$moduleUP.'_METADESCRIPTION_DSC','Indiquer la meta-description par défaut.');
define('_MI_'.$moduleUP.'_COM','Longueur des listes');
define('_MI_'.$moduleUP.'_COM_DSC','Préciser la longueur des tableaux.');
define('_MI_'.$moduleUP.'_JQUERY','Activation de jQuery');
define('_MI_'.$moduleUP.'_JQUERY_DSC','Activer le chargement du jQuery du module. Si jQuery est chargé dans votre thème, mettre ce choix à "OFF"');
define('_MI_'.$moduleUP.'_MAX_TAILLE_ITEM','Poids maximale des objets en téléchargement');
define('_MI_'.$moduleUP.'_MAX_TAILLE_ITEM_DESC', 'Indiquez la taille maximale autorisée en Mo');
define('_MI_'.$moduleUP.'_MAX_LARGEUR_PHOTO','Largeur image');
define('_MI_'.$moduleUP.'_MAX_LARGEUR_PHOTO_DESC','Largeur maximale de l\'image en pixels pour l\'upload');
define('_MI_'.$moduleUP.'_MAX_HAUTEUR_PHOTO','Hauteur image');
define('_MI_'.$moduleUP.'_MAX_HAUTEUR_PHOTO_DESC','Hauteur maximale de l\'image en pixels pour l\'upload');
define('_MI_'.$moduleUP.'_LARGEUR_THUMB','Largeur des vignettes');
define('_MI_'.$moduleUP.'_MAX_LARGEUR_THUMB_DESC','Largeur des vignettes en pixels');
define('_MI_'.$moduleUP.'_HAUTEUR_THUMB','Hauteur des vignettes');
define('_MI_'.$moduleUP.'_MAX_HAUTEUR_THUMB_DESC','Hauteur des vignettes en pixels');
// Template
define('_MI_'.$moduleUP.'_TEMPLATE_INDEX_DSC','Template de l\'index du module');
define('_MI_'.$moduleUP.'_TEMPLATE_CONTENT_DSC','Template des autres pages du module');
define('_MI_'.$moduleUP.'_TEMPLATE_FOOTER_DSC','Pied de page');
// Notifications
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_NOTIFY', 'Notifications de catégorie');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_NOTIFY_DSC', 'Notification s\'appliquant à la catégorie courante.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY', 'Nouvelle catégorie');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_CAP', 'Notifiez moi quand une catégorie est créée.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_DSC', 'Recevoir une notification quand une catégorie est créée.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: Nouvelle catégorie');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_NOTIFY', 'Notifications globales');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_NOTIFY_DSC', 'Options de notification s\'appliquant à toutes les catégories.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_PUBLISHED_NOTIFY', 'Nouveaux articles publiés');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_PUBLISHED_NOTIFY_CAP', 'Notifiez moi quand un article est publié.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_PUBLISHED_NOTIFY_DSC', 'Recevoir une notification quand un article est publié.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_PUBLISHED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: nouvel article publié');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_SUBMITTED_NOTIFY', 'Article soumis');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_SUBMITTED_NOTIFY_CAP', 'Notifiez moi quand un article est soumis et en attente d\'être approuvé.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_SUBMITTED_NOTIFY_DSC', 'Recevoir une notification quand un article est soumis et en attente d\'être approuvé.');
define('_MI_'.$moduleUP.'_GLOBAL_ITEM_SUBMITTED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: nouvel article soumis');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_PUBLISHED_NOTIFY', 'Nouveaux articles publiés');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_PUBLISHED_NOTIFY_CAP', 'Notifiez moi quand un nouvel article est publié dans cette catégorie.');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_PUBLISHED_NOTIFY_DSC', 'Recevoir une notification quand un nouvel article est publié dans cette catégorie.');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_PUBLISHED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: Nouvel article dans cette catégorie');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_SUBMITTED_NOTIFY', 'Article soumis');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_SUBMITTED_NOTIFY_CAP', 'Notifiez moi quand un nouvel article est soumis dans cette catégorie.');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_SUBMITTED_NOTIFY_DSC', 'Recevoir une notification quand un nouvel article est soumis dans cette catégorie.');
define('_MI_'.$moduleUP.'_CATEGORY_ITEM_SUBMITTED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: Nouvel article soumis dans cette catégorie');
define('_MI_'.$moduleUP.'_ITEM_APPROVED_NOTIFY', 'article approuvé');
define('_MI_'.$moduleUP.'_ITEM_APPROVED_NOTIFY_CAP', 'Notifiez moi quand cet article sera approuvé.');
define('_MI_'.$moduleUP.'_ITEM_APPROVED_NOTIFY_DSC', 'Recevoir une notification quand cet article sera approuvé.');
define('_MI_'.$moduleUP.'_ITEM_APPROVED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: article approuvé.');
define('_MI_'.$moduleUP.'_ITEM_NOTIFY', 'Articles');
define('_MI_'.$moduleUP.'_ITEM_NOTIFY_DSC', 'Option de notification s\'appliquant au présent article.');
define('_MI_'.$moduleUP.'_ITEM_REJECTED_NOTIFY', 'article rejeté');
define('_MI_'.$moduleUP.'_ITEM_REJECTED_NOTIFY_CAP', 'Notifiez moi si cet article est rejeté.');
define('_MI_'.$moduleUP.'_ITEM_REJECTED_NOTIFY_DSC', 'Recevoir une notification si cet article est rejeté.');
define('_MI_'.$moduleUP.'_ITEM_REJECTED_NOTIFY_SBJ', '[{X_SITENAME}] {X_MODULE} auto-notification: article rejeté');