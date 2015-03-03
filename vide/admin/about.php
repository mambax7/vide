<?php
/**
 * CHG-WEB - MONTUY337513 - 2014-08-15
 */
include_once 'admin_header.php';
$aboutAdmin = new ModuleAdmin();
echo $aboutAdmin->addNavigation('about.php');
echo $aboutAdmin->renderAbout('KK44NLXHR2MXW', false);
include 'admin_footer.php';
