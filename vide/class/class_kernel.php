<?php
class kernel{
    public $liste_table = array();
    protected $liste_class = array();
    protected $liste_variable = array();
    protected $divers = array();
    public $fin_page = array();
    public $DirName = ''; // Nom du module
    public $lcen = 1; // Compatible LCEN
    public $seo = 0; // Activation de l'URL REWRITING
    public $nom_script = '';
    public $utilisateur = '';
    public $en_provenance = ''; // D'où provient l'utilisateur
    public $url_en_cours = '';
    public $nbcomindex = 20;
    public $nbcom = 20;
    public $url_module = '';
    public $barenav = 0; // A 1 si barre de navigation
    public $pagenav = ''; // La barre de navigation
    public $largeur_popup = 600;
    // Class extérieur
    public $db = ''; // Class XoopsDB
    function __construct(){
        global $xoopsDB,$xoopsModuleConfig,$liste_class,$liste_table,$DirName,$query_string_commun;
        $this->liste_variable = $query_string_commun;
        $this->DirName = $DirName;
        $this->liste_table = $liste_table;
        $this->liste_class = $liste_class;
        $this->db = $xoopsDB;
        $this->lcen = $xoopsModuleConfig[$this->DirName.'_lcen'];
        //$this->seo = $xoopsModuleConfig[$this->DirName.'_seo'];
        $this->nbcomindex = $xoopsModuleConfig[$this->DirName.'_nb_comindex'];
        $this->nbcom = $xoopsModuleConfig[$this->DirName.'_nb_com'];
        $this->url_module = XOOPS_URL.'/modules/'.$this->DirName;
        $temp = $this->liste('divers');
        if (count($temp) > 0){
            foreach ($temp as $v){
                $this->divers[$v['nom']] = $v['valeur'];
            }
        }
        unset($temp);
    }
    // Ajout de variables d'entrée
    public function add_query($query){
        if (!is_array($query)) return false;
        $this->liste_variable = array_merge($this->liste_variable,$query);

        return true;
    }
    // Gestion des défauts
    public function defaut($valeur){
        switch($valeur){
            case'file':
                return 'blank.png';
            case'float':
                return 0.00;
            case 'area';
            case 'string';
            case 'text':
                return '';
            case'int':
                return 0;
            case'table':
                return array();
            default:
                return'';
        }
    }
    // Stripslashes les tableaux
    public function stripslashes_deep($value){
        $value = is_array($value) ? array_map('kernel::stripslashes_deep', $value) : stripslashes($value);

        return $value;
    }
    public function netvar($variable,$type='quote'){
        switch($type){
            case'table';
            case'groupe':
                if (is_array($variable)) return  $variable;

                return array();
            case'float':
                return $variable;
            case'url':
                return filter_var($variable,FILTER_SANITIZE_URL);
            case'int':
                return filter_var($variable, FILTER_SANITIZE_NUMBER_INT);
            case'mail':
                return filter_var($variable, FILTER_SANITIZE_EMAIL);
            case'sup_slash':
                return stripslashes($variable);
            case'slash':
                return filter_var($variable, FILTER_SANITIZE_MAGIC_QUOTES);
            case'string':
            default:
                return filter_var($variable, FILTER_SANITIZE_STRING);
        }
    }
    // Sécurise GET, POST et COOKIE
    public function requete($lieu,$nom,$defaut='',$sql=0){
        if (!array_key_exists($nom,$this->liste_variable)) return $defaut;
        switch($lieu){
            case'get':
                if (!isset($_GET[$nom])) {
                    return $defaut;
                } else {
                    $valeur = $this->netvar($_GET[$nom],$this->liste_variable[$nom]);
                }
                break;
            case'post':
                if (!isset($_POST[$nom]) ) {
                    return $defaut;
                } else {
                    $valeur = $this->netvar($_POST[$nom],$this->liste_variable[$nom]);
                }
                break;
            case'cookie':
                if (!isset($_COOKIE[$nom])) {
                    return $defaut;
                } else {
                    $valeur = $this->netvar($_COOKIE[$nom],$this->liste_variable[$nom]);
                }
                break;
            default:
                return $defaut;
        }
        if ($sql == 1){
            $valeur = $this->lutte_sql($valeur);
        }

        return $valeur;
    }
    // lutte contre les injections MySQL
    protected function lutte_sql($valeur){
        return htmlspecialchars($valeur,ENT_QUOTES|ENT_HTML5,'UTF-8');
    }
    // Prépare les données pour la BDD
    public function prep_sql($valeur,$type){
        switch($type){
            case'area';
            case'file';
            case'text';
            case'string':
                return '\''.addslashes($valeur).'\'';
            case'float';
            case'int':
                return $valeur;
            case'date';
            case'time';
            case'datetime':
                return '\''.$valeur.'\'';
            default:
                return '\'\'';
        }
    }
    // Récupération des valeurs du tableau
    public function valeur_config($cle){
        if (!array_key_exists($cle,$this->divers)) return false;

        return $this->divers[$cle];
    }
    // Mise a jour des dates de purge et de sauvegarde
    public function update_divers($cle,$valeur){
        $champs = array('nom','valeur');
        $val = array($cle,$valeur);
        if ($this->valeur_config($cle) === false){
            return $this->insert_sql('divers',$champs,$val);
        } else {
            return $this->modif_sql('divers',$champs,$val,'`nom`='.$cle);
        }
    }
    // Création d'une liste via MySQL via select
    public function liste($table,$where='',$order='',$extra=''){
        $list = array();
        if (!empty($where)) $where = ' WHERE '.$where;
        if (!empty($order)) $order = ' ORDER BY '.$order;
        $requete = $this->db->query('SELECT * FROM '.$this->db->prefix($this->liste_table[$table]).$where.$order.' '.$extra);
        while($temp = $this->db->fetchArray($requete)){
            $list[$temp['id']] = $this->stripslashes_deep($temp);
        }

        return $list;
    }
    // Supprime dans mysql
    protected function sup_sql($table,$where=''){
        if (!empty($where)) {
            $where = ' WHERE '.$where;
        }

        return $this->db->queryF('DELETE FROM '.$this->db->prefix($this->liste_table[$table]).$where);
    }
    // Inserer dans mysql
    protected function insert_sql($table,$champ,$valeur){
        if (is_array($champ) && is_array($valeur)){
            return $this->db->queryF('INSERT INTO '.$this->db->prefix($this->liste_table[$table]).' (`'.implode('`,`',$champ).'`) VALUES ('.implode(',',$valeur).')');
        } else {
            return false;
        }
    }
    // Mettre a jour dans MySQL
    protected  function modif_sql($table,$champ,$valeur,$where='',$extra=''){
        $temp = '';
        $premier = 0;
        if (is_array($champ) && is_array($valeur) && !empty($where)){
            foreach($champ as $k=>$v){
                if($premier == 1) {
                    $temp .= ',';
                }
                $temp .= '`'.$v.'`='.$valeur[$k].'';
                $premier = 1;
            }

            return $this->db->queryF('UPDATE '.$this->db->prefix($this->liste_table[$table]).' SET '.$temp.' '.$where.' '.$extra);
        }else{
            return false;
        }
    }
    // Fonction active et desactive
    public function active_desactive($id,$etat=0,$table=''){
        if (is_array($id)) {
            $extra = '`id` IN ('.implode(',',$id).')';
        } else {
            $extra = '`id`='.$id;
        }
        $temp = $this->db->queryF('UPDATE '.$this->db->prefix($this->liste_table[$table]).' SET actif=\''.$etat.'\' WHERE '.$extra);
        if ($etat == 1){
            $a = CHG_ACTIVATION;
        } else {
            $a = CHG_DESACTIVE;
        }
        if (is_array($id)){
            $b = implode(',',$id);
        }else{
            $b = $id;
        }
        if ($temp === false){
            $this->inscrit_log(CHG_LOG_ERRSQL,sprintf(CHG_LOG_ACT_DES_NOTOK,$a,$b,$table));

            return false;
        }else{

            $this->inscrit_log(CHG_LOG_OPSQL,sprintf(CHG_LOG_ACT_DES,$a,$b,$table));

            return true;
        }
    }
    // Compte les lignes d'une table
    public function compte($table,$id,$where='',$extra=''){
        if (!empty($where)) $where = ' WHERE '.$where;
        if (!empty($extra)) $extra = ' '.$extra;
        $temp = $this->db->query('SELECT count(`'.$id.'`) FROM '.$this->db->prefix($this->liste_table[$table]).$where.$extra);
        $resultat = $this->db->fetchrow($temp);

        return $resultat[0];
    }
    // Test si un un id est présent
    public function test_presence($table,$cle='id',$valeur=0){
        if ($valeur == 0) return false;
        if(!array_key_exists($table,$this->liste_table)) return false;
        if($this->compte($table,$cle,'`'.$cle.'`='.$valeur) == 1) return true;

        return false;
    }
    function chg_substr($str, $start, $length, $trimmarker = '...'){
        if (! XOOPS_USE_MULTIBYTES) {
            return (strlen($str) - $start <= $length) ? substr($str, $start, $length) : substr($str, $start, $length - strlen($trimmarker)) . $trimmarker;
        }
        if (function_exists('mb_internal_encoding') && @mb_internal_encoding(_CHARSET)) {
            $str2 = mb_strcut($str, $start, $length - strlen($trimmarker));

            return $str2 . (mb_strlen($str) != mb_strlen($str2) ? $trimmarker : '');
        }

        return $str;
    }
    // test les valeurs ON/OFF
    public function onoff($valeur){
        if(!ctype_digit($valeur)) return false;
        if ($valeur == 0 || $valeur == 1) return true;

        return false;
    }
    // Test le nombre de caractères
    public function min_max($titre,$min,$max){
        if (!is_numeric($min) OR !is_numeric($max)) return false;
        if (is_numeric($titre)) {
            $n = $titre;
        } else {
            $n = strlen($titre);
        }
        if ($n >= $min && $n <= $max) return true;

        return false;
    }
    // Inscription dans le log
    public function inscrit_log($type,$description){
        $champ = array('titre','created','description','userid','ip','url','provenance');
        $valeur = array('\''.$type.'\'','NOW()',$this->prep_sql($description,'text'),'\''.$this->utilisateur.'\'','\''.$_SERVER['REMOTE_ADDR'].'\'','\''.$this->url_en_cours.'\'','\''.$this->en_provenance.'\'');
        if($this->insert_sql('log',$champ,$valeur)) {
            return true;
        } else {
            return false;
        }
    }
    //********************************************************** Système d'affichage
    // Création titre
    public function titre($titre,$type='div'){
        switch ($type){
            case'h1':
                return '<h1>'.$titre.'</h1>';
            case'div':
                return '<div id="CHG_titre"><h1>'.$titre.'</h1></div>';
        }

        return $titre;
    }
    // Creation de bouton pour tableau
    public function aff_button($nom,$titre,$id=''){
        $id1 = (empty($id)) ? '' : '&amp;id='.$id;

        return '<button class="CHG_button" onClick="self.location.href=\'?op='.$nom.$id1.'\'">'.$titre.'</button>';
    }
    // Création des pagesnav
    function num_page($nb_ligne=0,$numero_page=1,$style='simple',$nb=1){
        global $xoopsModuleConfig;
        if ($style == 'index'){
            $style = 'simple';
            $tempo = $xoopsModuleConfig[$this->DirName.'_nb_comindex'];
        } else {
            $tempo = $xoopsModuleConfig[$this->DirName.'_nb_com'];
        }
        switch($style){
            case'simple':
            default:
                $next = '';
                $prev = '';
                if ($nb_ligne == 0 OR $numero_page < 1){
                    $this->pagenav = '';

                    return true;
                }
                $tot_page = ceil($nb_ligne/$tempo);
                if ($tot_page == 1){
                    $this->pagenav = '';

                    return true;
                }
                if ($numero_page == 1) {
                    $next = '<a href="?page='.($numero_page+1).'" title="'.CHG_SUIVANT.'">'.($numero_page+1).'</a>';
                }elseif ($numero_page == $tot_page){
                    $prev = '<a href="?page='.($numero_page-1).'" title="'.CHG_PRECEDENT.'">'.($numero_page-1).'</a>';
                } else {
                    $next = '<a href="?page='.($numero_page+1).'" title="'.CHG_SUIVANT.'">'.($numero_page+1).'</a>';
                    $prev = '<a href="?page='.($numero_page-1).'" title="'.CHG_PRECEDENT.'">'.($numero_page-1).'</a>';
                }
        }
        $this->pagenav = '<div class="chg_pagenav">'.$prev.'<span>('.$numero_page.')</span>'.$next.'</div>';
        $this->barenav = 1;

        return true;
    }
    public function gest_date($datetime,$format=0){
        $day = $month = $year = $hour = $min = $sec = $time = '';
        if($format != 3){
            list($date, $time) = explode(' ', $datetime);
            list($year, $month, $day) = explode('-', $date);
            list($hour, $min, $sec) = explode(':', $time);
        }
        switch($format){
            case'3':
                return date(_DATESTRING, $datetime);
                break;
            case'2': // simple
                return date(_DATESTRING, strtotime($datetime));
                break;
            case'1': // Mois en entier et heure complète
                $months = array(_JANV, _FEV, _MAR, _AVR, _MAI, _JUI,_JUL, _AOU, _SEP, _OCT, _NOV, _DEC);

                return $day.' '.$months[$month-1].' '.$year.' '.CHG_AT.' '.$hour.'h'.$min.'m'.$sec.'s';
                break;
            case'0'; // format francophone
            default:
                return $day.'/'.$month.'/'.$year.' '.$time;
        }
    }
    // Mise en forme des cases d'un tableau
    public function cellule($type,$valeur){
        switch($type){
            case'image':
                return '<img src="'.URL_THUMB_CAT.'/'.$valeur.'" />';
            case'onoff':
                if ($valeur == 1){
                    return '<img src="'.$this->url_module.'/images/ok.png" />';
                }

                return '';
                break;
            case'string':
                return stripslashes($valeur);
                break;
            case'date':
                return $this->gest_date($valeur,2);
            case'list_cat':
                global $moduleUP;
                if($valeur == 0) {
                    return  constant('_AM_'.$moduleUP.'_CAT_PRINC');
                }else{
                    $list = $this->liste('categorie','`id`='.$valeur);
                    if (count($list) > 0) {
                        return  $list[$valeur]['nom'];
                    }else{
                        return constant('_AM_'.$moduleUP.'_CAT_INC');
                    }
                }
                break;
            default:
                return $valeur;
        }
    }
    // Création des menu de confirmation de suppression
    public function conf_sup($id,$titre,$cible){
        ob_start();
        xoops_confirm(array( 'op' => 'conf_sup', 'id' => $id, 'ok' => 1),$this->url_module.$cible,sprintf(CHG_CONFIRM_SUP,$titre));
        $content = '<div>'.ob_get_contents().'</div>';
        ob_end_clean();

        return $content;
    }
    public function lien_voirplus($value,$contenu='',$nom_div='popup',$type=1){ //$type = 0 => image, $type = 1 => texte
        $nb = count($this->fin_page);
        $this->fin_page[] = '<div id="'.$nom_div.'_'.$nb.'" class="popup_block" >'.$contenu.'</div>';
        switch($type){
            case 0:
                $texte = '<img src="'.$this->url_module.'/images/'.$value.'" />';
                break;
            case 1;
            default :
                $texte = $value;
        }

        return '<p class="center"><a href="#" data-width="'.$this->largeur_popup.'" data-rel="'.$nom_div.'_'.$nb.'" class="poplight" style="color: #384313;">'.$texte.'</a></p>';
    }
    // Affichage de la fiche LOG
    public function aff_tout_log($tableau){
        global $moduleUP;
        $content = '<p style="center">'.$tableau['titre'] .'</p>';
        $content .= '<p>'.$tableau['created'].'</p>';
        $content .= '<p><span class="gras">'.CHG_DESC.' : </span>'.$tableau['description'].'</p>';
        $userid = (empty($tableau['userid'])) ? '' : $tableau['userid'];
        $content .= '<p><span class="gras">'.CHG_USERID.' : </span>'.$userid.'</p>';
        $content .= '<p><span class="gras">'.constant('_AM_'.$moduleUP.'_IP').' : </span>'.$tableau['ip'].'</p>';
        $content .= '<p><span class="gras">'.constant('_AM_'.$moduleUP.'_URL').' : </span>'.$tableau['url'].'</p>';
        $content .= '<p><span class="gras">'.constant('_AM_'.$moduleUP.'_REFFERER').' : </span>'.$tableau['provenance'].'</p>';

        return $content;
    }
    public function aff_tout_cat($tableau){
        global $moduleUP;
        $content = '<p class="center" style="font-weight: bold;">'.$tableau['id'].' - '.$tableau['nom'] .'</p>';
        $content .= '<p>'.$tableau['created'].'</p>';
        $content .= '<p><span class="gras">'.CHG_DESC.' : </span><br />'.$tableau['descriptif'].'</p>';
        $content .= '<p><span class="gras">'.CHG_KEYWORDS.' : </span><br />'.$tableau['keywords'].'</p>';
        $content .= '<p><span class="gras">'.constant('_AM_'.$moduleUP.'_PID').' :</span><br />'.$this->cellule('list_cat',$tableau['pid']).'</p>';
        $content .= '<p><span class="gras"><img src="'.URL_THUMB_CAT.'/'. $tableau['image'].'" /></p>';
        $content .= '<p><span class="gras">'.CHG_ACTF.' : </span>'.$tableau['actif'].'</p>';

        return $content;
    }
    public function aff_tout_item($tableau){
        global $moduleUP;
        $content = '<p class="center" style="font-weight: bold;">'.$tableau['id'].' - '.$tableau['nom'] .'</p>';
        $content .= '<p>'.$tableau['created'].'</p>';
        $content .= '<p><span class="gras">'.CHG_DESC.' : </span><br />'.$tableau['descriptif'].'</p>';
        $content .= '<p><span class="gras">'.CHG_KEYWORDS.' : </span><br />'.$tableau['keywords'].'</p>';
        $content .= '<p><span class="gras">'.constant('_AM_'.$moduleUP.'_PID').' :</span><br />'.$this->cellule('list_cat',$tableau['pid']).'</p>';
        $content .= '<p><span class="gras"><a href="'.URL_ITEM.'/'.$tableau['fichier'].'" >'.$tableau['fichier'].'</a></p>';
        $content .= '<p><span class="gras">'.CHG_ACTF.' : </span>'.$tableau['actif'].'</p>';

        return $content;
    }
    // Création des titres "ajouter X"
    public function ajout($nom){
        return CHG_ADD.' '.mb_strtolower($nom,'UTF-8');
    }
    // Création des titres "modifier X"
    public function modif($nom){
        return CHG_MODIF.' '.mb_strtolower($nom,'UTF-8');
    }
}
