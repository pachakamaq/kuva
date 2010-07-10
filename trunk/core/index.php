<?php
$_SERVER['DOCUMENT_ROOT'] = realpath(str_replace(str_replace($_SERVER['PATH_INFO'], '', ereg_replace('/+', '/', $_SERVER['PHP_SELF'])), '', $_SERVER['SCRIPT_FILENAME']));

// http://dev.kanngard.net/Permalinks/ID_20050507183447.html
function selfURL() {
    $s = empty($_SERVER["HTTPS"]) || ($_SERVER["HTTPS"] != "on") ? '' : 's';
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}
function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function parse($file){
    ob_start();
    require $file;
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

/**
 * DOMCdataSection findCData(DOMElement $element, [boolean $removeOthers])
 * 
 * Returns the first CDATA node which is an immediate child of $element, or NULL
 * if none is found.
 */
function findCData($element){
    if($element->childNodes->length < 4){
        for($i = 0; $i < $element->childNodes->length; ++$i){
            $child = $element->childNodes->item($i);
            $endl = '<br />' . PHP_EOL;
            if($child->nodeType == 4){
                return $child;
            }
        }
    }
    return NULL;
}

/**
 * DOMDocument extractElements(string $file)
 * 
 * Calls the PHP parser on the contents of the file at $file, then constructs an
 * XML document from the results.  Each immediate child of the document element
 * is added to the global $variables associative array, if and only if it
 * contains a CDATA node within its first three children (including whitespace
 * as text nodes).
 * 
 * Each element which is the first with a given tag name is added directly to
 * $variables, with that tag name as its key.  If a second element with that tag
 * name is found, the first is replaced with an array which contains both
 * elements.  Each subsequent element with that tag name is appended to this
 * array.
 * 
 * Returns the XML document which was constructed at the start, unmodified.
 */
function extractElements($file, &$array){
    $xml = new DOMDocument();
    $source = parse($file);
    $xml->loadXML($source);
    $children = $xml->documentElement->childNodes;
    for($i0 = 0; $i0 < $children->length; ++$i0){
        $child = $children->item($i0);
        if($child->hasAttributes() || findCData($child) != NULL){
            $name = strtoupper($child->tagName);
            if(array_key_exists($name, $array)){
                if(is_array($array[$name])){
                    array_push($array[$name], $child);
                }else{
                    $array[$name] = array($array[$name], $child);
                }
            }else{
                $array[$name] = $child;
            }
        }
    }
    return $xml;
}

/**
 * array $variables
 * 
 * Contains all the pieces from which the page will be assembled, including
 * those that templates don't necessarily care about.  Regardless, they are
 * all available to templates in the form {$NAME$} where "NAME" is the index
 * within $variables of the piece to be used.
 * 
 * (The static initialization of MENU will eventually be replaced with a script
 * which indexes the site for pages and automatically generates a menu.)
 */
$variables = array('MENU' => <<<MENU
<ul id="menu">
                    <li>
                        <a href="/">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="/?/about.php">
                            About
                        </a>
                    </li>
                </ul>
MENU
);

if($_SERVER['QUERY_STRING'] == ''){
    $file = 'index.php';
}else{
    $file = $_SERVER['QUERY_STRING'];
}
$file = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . preg_replace('/\.php$/',
    '.xml', $file));

/*
 * Eventually I think I'd like to use the DOM to assemble the entire page. But
 * for now it's only used to easily find child nodes.
 */
$page = extractElements($file, $variables);
$template = extractElements(realpath($_SERVER['DOCUMENT_ROOT'] . '/kuva/templates/default.xml'), $variables);

/**
 * void processStyle(DOMElement $style)
 * 
 * Organizes the contents of a 'style' element within the $styles
 * array according to which media ty$components['BODY']pe it is intended for, and
 * whether it links to an external stylesheet or contains one within
 * itself.
 * 
 * The $styles array contains two branches, 'ext' and 'int', within
 * each of which the media types indicated on all style elements
 * represented on that branch are keys associated with numeric arrays
 * of the corresponding style elements' representations.
 * 
 * External styles are recognized by the presence of the 'src'
 * attribute, and are represented within the 'ext' branch as the
 * value of that attribute.  Each other is considered internal and
 * represented on the 'int' branch by the first CDATA node which is
 * found as its child. Currently, styles which possess neither 'src'
 * attributes nor CDATA children are silently ignored.
 */
function processStyle($style){
    global $styles;
    $src = $style->getAttribute('src');
    $media = $style->getAttribute('media');
    if($src == NULL){
        $cData = findCData($style);
        if($cData != NULL){
            if(array_key_exists($media, $styles['int'])){
                array_push($styles['int'][$media], $cData->data);
            }else{
                $styles['int'][$media] = array($cData->data);
            }
        }
    }else{
        if(array_key_exists($media->value, $styles['ext'])){
            array_push($styles['ext'][$media], $src);
        }else{
            $styles['ext'][$media] = array($src);
        }
    }
}
if(array_key_exists('STYLE', $variables)){
    // This just runs through every style and makes sure it's represented.
    $styles = array('ext' => array(), 'int' => array());
    if(is_array($variables['STYLE'])){
        foreach($variables['STYLE'] as $style){
            processStyle($style);
        }
    }else{
        processStyle($variables['STYLE']);
    }

    /*
     * Now we convert each style to its HTML representation and put it
     * back in the $variables array.
     */
    $variables['STYLE'] = '';
    foreach($styles['ext'] as $media => $exts){
        $media = $media == NULL ? '' : " media=\"$media\"";
        foreach($exts as $src){
            $variables['STYLE'] .= "<link rel=\"stylesheet\" type=\"text/css\"$media href=\"$src\" />" . PHP_EOL;
        }
    }
    foreach($styles['int'] as $media => $ints){
        $media = $media == NULL ? '' : " media=\"$media\"";
        foreach($ints as $src){
            $variables['STYLE'] .= "<style type=\"text/css\"$media>" . PHP_EOL . $src . PHP_EOL . '</style>' . PHP_EOL;
        }
    }
}

/* 
 * There's only one instance of each of these elements, so we can just unwrap
 * their CDATA.
 */
foreach($variables as $key => $value){
    if(is_a($value, 'DOMNode')){
        $variables[$key] = findCData($value)->data;
    }
}
// No need to do this every iteration of the below for-loop.
$keys = array_keys($variables);
$values = array_values($variables);

// Derive the in-template syntax for including the $variables.
foreach($keys as $i => $key){
    $keys[$i] = '{$' . $key . '$}';
}
// It's show time.
$variables['BODY'] = str_replace($keys, $values, $variables['BODY']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $variables['TITLE']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php echo $variables['STYLE']; ?>
        
        <style type="text/css" media="all">
            #kuva{
                margin-bottom: 0;
                clear: both;
                text-align: center;
                }
        </style>
    </head>
    <body>
        <?php echo $variables['BODY']; ?>

        <div id="kuva">
            <br />
            Powered by <a href="http://code.google.com/p/kuva/">Kuva</a>; see <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html">license</a>.
        </div>
    </body>
</html>
