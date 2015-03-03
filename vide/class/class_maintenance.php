<?php
class maintenance {
    public $prefix = '';
    public $resultat = '';
    public $path_file = '';
    public $sequence = array();
    public function dump_table($table='') {
        if(!is_array($table)) {
            return;
        }
        $this->prefix = $this->db->prefix.'_';
        $this->sequence[] = '# Généré par le module "'.$this->DirName.'"';
        $this->sequence[] = '# Le'.date('d M Y H:i:s');
        $this->sequence[] = '# Par CHG-WEB - http://www.chg-web.com';
        $this->sequence[] = '#';
        foreach ($table as $v) {
            $result = $this->db->queryF('SHOW create table `'.$this->prefix.$v.'`;');
            if( $result ) {
                if( $row = $this->db->fetchArray($result) ) {
                    $this->sequence[] = '# Table structure for table `'.$this->prefix.$v.'` '."\n\n";
                    $this->sequence[] = 'DROP TABLE IF EXISTS `'.$this->prefix.$v.'`;'."\n\n";
                    $this->sequence[] = $row['Create Table'].";\n\n";
                }
            }
            $this->db->freeRecordSet($result);
            $result = $this->db->queryF('SELECT * FROM '.$this->prefix.$v.';');
            if( $result) {
                $num_rows= $this->db->getRowsNum($result);
                $num_fields= $this->db->getFieldsNum($result);

                $count = 0;
                if( $num_rows > 0) {
                    $field_type = array();
                    $i = 0;
                    while( $i < $num_fields ) {
                        $meta = mysql_fetch_field($result, $i);
                        array_push($field_type, $meta->type);
                        $i++;
                    }

                    $this->sequence[] = 'INSERT INTO `'.$this->prefix.$v.'` VALUES'."\n";
                    $index = 0;
                    while( $row = $this->db->fetchRow($result) ) {
                        $count++;
                        $this->sequence[] = '(';
                        for( $i=0; $i < $num_fields; $i++ ) {
                            if( is_null( $row[$i] ) ) {
                                $this->sequence[] = 'NULL';
                            } else {
                                switch( $field_type[$i]) {
                                    case 'int':
                                        $this->sequence[] = $row[$i];
                                        break;
                                    default:
                                        $this->sequence[] = "'".mysql_real_escape_string($row[$i])."'";
                                }
                            }
                            if( $i < $num_fields-1) {
                                $this->sequence[] = ',';
                            }
                        }
                        $this->sequence[] = ')';
                        if( $index < $num_rows-1 ) {
                            $this->sequence[] = ',';
                        } else {
                            $this->sequence[] = ';';
                        }
                        $this->sequence[] = "\n";
                        $index++;
                    }
                }
            }
            $this->db->freeRecordSet($result);
            $this->sequence[] = "\n";
        }
    }
    public function resultat_table_bdd() {
        foreach ($this->sequence as $v) {
            $this->resultat .= $v;
        }

        return $this->resultat;
    }
    public function create_file_dump($uri_sauve) {
        $file_name = '/dump_'.date('Y.m.d').'_'.date('H.i.s').'.sql';
        $this->path_file = $uri_sauve.$file_name;

        return file_put_contents($this->path_file, $this->resultat);
    }
    // Nettoyage et optimisation des tables du modules
    public function clean_bdd(){
        $this->prefix = $this->db->prefix.'_';
        $liste = '';
        foreach($this->liste_table as $v){
            if (!empty($liste)) {
                $liste .= ', ';
            }
            $liste .= $this->db->prefix($v);
        }
        $this->db->queryF('OPTIMIZE TABLE '.$liste);
        $this->db->queryF('CHECK TABLE '.$liste);
        $this->db->queryF('ANALYZE TABLE '.$liste);
    }
    // Vidange des logs
    public function purge_log_systeme(){
        return $this->sup_sql('log');
    }
}
