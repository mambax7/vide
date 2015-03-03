<?php
class categorie{
    protected $liste_cat = array();
    protected $table_cat = array(
        'id' => 'int',
        'pid' => 'int',
        'nom' => 'string',
        'created' => 'date',
        'description' => 'area',
        'keywords' => 'area',
        'image' => 'file',
        'actif' => 'int'
    );
    public function arbre_cat($pid=0,$etape=0,$tout=0){
        $tiret = ($etape == 0) ? '' : str_repeat('- ',$etape);
        $where = ($pid == 0) ? '`pid`=0' : '`pid`='.$pid;
        $extra = ($tout == 0) ? ' AND `actif`=1' : '';
        $temp = $this->liste('categorie',$where.$extra, '`nom` ASC');
        if (count($temp) > 0){
            foreach ($temp as $k=>$v){
                $this->liste_cat[$v['id']] = $tiret.$v['nom'];
                $etape++;
                $this->arbre_cat($v['id'],$etape,$tout);
            }
        }

        return true;
    }
    function liste_categorie($id=0){
        if (count($this->liste_cat) > 0) return $this->liste_cat;
        $this->arbre_cat($id);

        return $this->liste_cat;
    }
    public function crea_categorie($pid,$nom,$descriptif,$keywords,$image,$actif){
        $champs = array();
        $valeur = array();
        $n = 0;
        foreach($this->table_cat as $k=>$v){
            if (isset($$k)){
                $valeur[$n] = $this->prep_sql($$k,$v);
            } elseif($k == 'created') {
                $valeur[$n] = 'NOW()';
            } else{
                $valeur[$n] = '\'\'';
            }
            $champs[$n] = $k;
            $n++;
        }

        return $this->insert_sql('categorie',$champs,$valeur);
    }
    public function modif_categorie($id,$pid,$nom,$descriptif,$keywords,$image,$actif){
        $champs = array();
        $valeur = array();
        $n = 0;
        foreach($this->table_cat as $k=>$v){
            if ($k != 'created'){
                if (isset($$k)){
                    $valeur[$n] = $this->prep_sql($$k,$v);
                }else{
                    $valeur[$n] = '\'\'';
                }
                $champs[$n] = $k;
                $n++;
            }
        }

        return ($this->modif_sql('categorie',$champs,$valeur,' WHERE `id`='.$id));
    }
    public function del_cat($id){
        $liste = $this->liste('categorie','`id`='.$id);
        if(count($liste) == 0) return false;
        while (list($key, $val) = each($list)) {
            @unlink(URI_THUMB_CAT.'/'.$val['image']);
        }

        return $this->sup_sql('categorie','`id`='.$id);

    }
}
