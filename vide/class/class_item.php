<?php
class item{
    protected $table_item = array(
        'id' => 'int',
        'cid' => 'int',
        'nom' => 'string',
        'created' => 'date',
        'descriptif' => 'area',
        'keywords' => 'area',
        'fichier' => 'file',
        'actif' => 'int'
    );
    public function crea_item($cid,$nom,$descriptif,$keywords,$fichier,$actif){
        $champs = array();
        $valeur = array();
        $n = 0;
        foreach($this->table_item as $k=>$v){
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

        return $this->insert_sql('item',$champs,$valeur);
    }
    public function modif_item($id,$cid,$nom,$descriptif,$keywords,$image,$actif){
        $champs = array();
        $valeur = array();
        $n = 0;
        foreach($this->table_item as $k=>$v){
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

        return ($this->modif_sql('item',$champs,$valeur,' WHERE `id`='.$id));
    }
    public function del_item($id){
        return $this->sup_sql('item','`id`='.$id);

    }
}
