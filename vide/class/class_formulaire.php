<?php
class formulaire {
    public $erreur_formulaire = array();
    // Ajoute les erreurs cotés de l'extérieur
    public function add_erreur($erreur){
        $this->erreur_formulaire = array_merge($erreur,$this->erreur_formulaire);
    }
    // Affiche les messages erreurs cotée client
    public function aff_mess_client(){
        $temp = '';
        if (count($this->erreur_formulaire) > 0) {
            foreach ($this->erreur_formulaire as $v) {
                $temp .= '<script language="javascript">'."\n".'<!-- '."\n".'$.notifier.broadcast({ttl:\''.CHG_ERREUR.'\', msg:\''.addslashes($v).'\'});'."\n".'// -->'."\n".'</script>'."\n";
            }
        }
        return $temp;
    }
    // Invente un nom de fichier (pour les fichiers uploadés)
    public function createUploadName($folder,$filename, $trimname=false){
        $uid = '';
        $workingfolder = $folder;
        if($this->chg_substr($workingfolder,strlen($workingfolder)-1,1)<>'/') {
            $workingfolder .= '/';
        }
        $ext = basename($filename);
        $ext = explode('.', $ext);
        $ext= '.'.$ext[count($ext)-1];
        $true = true;
        while($true){
            $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
            list($usec, $sec) = explode(' ',microtime());
            $usec = (integer) ($usec * 65536);
            $sec = ((integer) $sec) & 0xFFFF;
            if($trimname) {
                $uid = sprintf("%06x%04x%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = sprintf("%08x-%04x-%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if(!file_exists($workingfolder.$uid.$ext)){
                $true = false;
            }
        }
        return $uid.$ext;
    }
    // Retaille les images
    public function chg_resizePicture($src_path , $dst_path, $param_width , $param_height, $keep_original = false, $fit = 'inside',$watermark=false) {
        $a = 'v';
        if (is_file(XOOPS_ROOT_PATH.'/Frameworks/WideImage/WideImage.php')){
            $a = XOOPS_ROOT_PATH;
        }elseif(!is_file(XOOPS_PATH.'/Frameworks/WideImage/WideImage.php')){
            $a = XOOPS_PATH;
        }

        if ($a == 'v'){
            $this->inscrit_log('ERREUR_FRAMEWORKS','Frameworks');
            return false;
        }
        require_once $a.'/Frameworks/WideImage/WideImage.php';
        $resize = true;
        $pictureDimensions = getimagesize($src_path);
        if (is_array($pictureDimensions)) {
            $pictureWidth = $pictureDimensions[0];
            $pictureHeight = $pictureDimensions[1];
            if ($pictureWidth < $param_width && $pictureHeight < $param_height) {
                $resize = false;
            }
        }

        $img = wideImage::load($src_path);
        if ($watermark == true){
            global $xoopsModuleConfig,$uri_icons;
            $watermark = WideImage::load($uri_icons.$xoopsModuleConfig[$this->DirName.'_uri_water']);
            $new = $img->merge($watermark,10,10,30);
            $new->saveToFile($src_path);
        }
        if ($resize) {
            $result = $img->resize($param_width, $param_height, $fit);
            $result->saveToFile($dst_path);
        } else {
            @copy($src_path, $dst_path);
        }
        if(!$keep_original) {
            @unlink( $src_path ) ;
        }
        return true;
    }
}
