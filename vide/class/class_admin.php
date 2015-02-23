<?php
class admin {
    protected $admin_header = array();
    protected $content = array();
    protected $id_tableau = 'tableau_admin';
    public $selection_rapide = 1; // A 1 pour une colonne de sélection rapide
    protected $creation = 0; // A 1 si le début du tableau est créé
    // Ajoute une ligne au <header>
    public function add_admin_header($ligne){
        if (!in_array($ligne,$this->admin_header)) $this->admin_header[] = $ligne;
    }
    // Affichage des meta coté admin
    public function aff_admin_header(){
        return implode("\n",$this->admin_header);
    }
    public function entete_tableau($add=0,$titre_add='',$titre_tableau=''){
        $this->creation = 1;
        $this->content[] = '<div id="CHG_content">';
        $this->content[] = $this->titre($titre_tableau,'div');
        // Met le bouton ajouter
        if ($add == 1){
            $this->content[] = '<div class="floatright">'.$this->aff_button('ajouter',$titre_add , '').'</div>';
        }
    }
    public function aff_tableau(){
        if ($this->creation == 1){
            $this->content[] = '</div>';
        }
        return implode("\n",$this->content);
    }
    public function crea_tableau_admin($table='',$id='tableau_admin',$liste='',$action='',$rapide=false,$cle='id'){
        if (!is_array($table) OR !is_array($liste) OR !is_array($action)) {
            return false;
        }
        $tableau = array();
        $id = (empty($id)) ? '' : 'id="'.$id.'"';
        if ($rapide){
            $tableau[] = '<form id="form_admin" method="post" action="'.$this->nom_script.'">';
        }
        $tableau[] = '<table '.$id.' class="border" style="margin-bottom: 10px;">';
        $tableau[] = '<thead>';
        $tableau[] = '<tr class="padding10 big black center line140" >';
        if ($rapide){
            $tableau[] = '<th class="w25"><input type="checkbox" id="tout" name="tout" /></th>';
        }
        foreach ($liste as $u) {
            $tableau[] = '<th class="'.$u['class'].'">'.$u['trad'].'</th>';
        }
        unset($u);
        if (count($action) > 0){
            $tableau[] = '<th style="width: 150px;">'.CHG_ACTIONS.'</th></tr></thead>';
        }
        $tableau[] = '<tbody>';
        $premier = 0;
        foreach ($table as $v) {
            if ($premier == 0) {
                $tableau[] = '<tr class="line140 border" style="border-bottom: 1px solid;">';
                $premier = 1;
            }else {
                $tableau[] = '</tr><tr class="line140 border" style="border-bottom: 1px solid;">';
            }
            if ($rapide){
                $tableau[] = '<td class="w25"><input type="checkbox" id="id" name="id['.$v['id'].']" />';
            }
            foreach($liste as $u){
                $tableau[] = '<td class="'.$u['class'].'">'.$this->cellule($u['type'],$v[$u['champs']]).'</td>';
            }
            if (count($action)>0){
                $tableau[] = '<td class="action">';
                if (array_key_exists('actif',$v)){
                    if($v['actif'] == 0){
                        $tableau[] = $this->aff_button('activer',CHG_ACTIVATION,$v[$cle]);
                    }else{
                        $tableau[] = $this->aff_button('desactiver',CHG_DESACTIVE,$v[$cle]);
                    }
                }
            }
            if (array_key_exists('operation',$v)){
                $tableau[] = $this->aff_button($v['operation'],$v['operation_titre'],$v[$cle]);
            }
            foreach ($action as $k=>$t){
                if ($k == 'supprimer' && $this->lcen == 1){

                } elseif ($k == 'voir'){
                    $tableau[] = $this->lien_voirplus($t,$this->aff_tout_log($v),'popup_'.$v[$cle],1);
                } elseif ($k == 'voir1'){
                    $tableau[] = $this->lien_voirplus($t,$this->aff_tout_cat($v),'popup_'.$v[$cle],1);
                } elseif ($k == 'voir2'){
                    $tableau[] = $this->lien_voirplus($t,$this->aff_tout_item($v),'popup_'.$v[$cle],1);
                } else{
                    $tableau[] = $this->aff_button($k,$t,$v[$cle]);
                }
            }
            $tableau[] = '</td>';
        }
        if(count($table) < 1){
            $tableau[] = '</tr><tr class="line140 border">'."\n";
            $tableau[] = '<td colspan="'.(count($liste) + 1).'" class="center italic">'.CHG_NO_DONNEE.'</td>';
        }
        $tableau[] = '</tr></tbody></table>';
        $this->content[] = implode("\n",$tableau);
        return true;
    }
    public function requis($requis){
        $tableau = array();
        global $moduleUP;
        $tableau[0]['present'] = CHG_PRESENT;
        $tableau[0]['couleur'] = 'green';
        $tableau[0]['titre'] = '';
        switch($requis){
            case'CHG_mentions':
                if (!is_dir(XOOPS_ROOT_PATH.'/modules/CHG_mentions')){
                    $tableau[0]['present'] = CHG_ABSENT;
                    $tableau[0]['couleur'] = 'red';
                }
                break;
            case'tcpdf':
                $tableau[0]['titre'] = constant('_AM_'.$moduleUP.'_TCPDF');
                if (!is_file(XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php')) {
                    $tableau[0]['present'] = CHG_ABSENT;
                    $tableau[0]['couleur'] = 'red';

                }
                break;
            case'wideimage':
                $tableau[0]['titre'] = constant('_AM_'.$moduleUP.'_WIDEIMAGE');
                if (!is_file(XOOPS_ROOT_PATH.'/Frameworks/WideImage/WideImage.php')) {
                    $tableau[0]['present'] = CHG_ABSENT;
                    $tableau[0]['couleur'] = 'red';
                }
                break;
            case'gd2':
                $tableau[0]['titre'] = constant('_AM_'.$moduleUP.'_GD2');
                if (function_exists('gd_info')) {
                    $gd = gd_info();
                    preg_match('/\d/', $gd['GD Version'], $match);
                    $gd_ver = $match[0];
                    if ($gd_ver > '2') {
                        $tableau[0]['present'] = sprintf(CHG_VERSION_OBSOLETE,$gd['GD Version']);
                        $tableau[0]['couleur'] = 'red';
                    }
                } else {
                    $tableau[0]['present'] = CHG_ABSENT;
                    $tableau[0]['couleur'] = 'red';
                }
                break;
            default:
                $tableau[0]['present'] = '';
                $tableau[0]['couleur'] = '';
        }
        return $tableau;
    }
}
 