<?php
    $dir = dirname(__FILE__);
    require $dir . '/phpsvnclient_patch.php';
    $sources = array();
    $dom = extractElements($dir . '/config.xml', $sources);
    foreach($sources['SOURCE'] as $source){
        $user = $source->hasAttribute('user') ? $source->getAttribute('user') : NULL;
        $pass = $source->hasAttribute('pass') ? $source->getAttribute('pass') : NULL;
        $phpsvnclient = new phpsvnclient($source->getAttribute('url'), $user, $password);
        $phpsvnclient->checkOut('trunk', $source->getAttribute('local'));
    }
?>
