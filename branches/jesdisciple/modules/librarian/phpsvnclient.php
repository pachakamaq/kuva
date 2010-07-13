<?php
    $dir = dirname(__FILE__);
    require $_SERVER['DOCUMENT_ROOT'] . '/phpsvnclient/phpsvnclient.php';
    $sources = array();
    $dom = extractElements($dir . '/config.xml', $sources);
    foreach($sources['SOURCE'] as $source){
        $user = $source->hasAttribute('user') ? $source->getAttribute('user') : NULL;
        $pass = $source->hasAttribute('pass') ? $source->getAttribute('pass') : NULL;
        $phpsvnclient = new phpsvnclient($source->getAttribute('url'), $user, $password);
        for($i = 0; $i < $source->childNodes->length; ++$i){
            if($source->childNodes->item($i)->nodeType == 1){
                $dir = $source->childNodes->item($i);
                $phpsvnclient->checkOut($dir->getAttribute('remote'), $dir->getAttribute('local'));
            }
        }
    }
?>
