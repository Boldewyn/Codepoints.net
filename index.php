<?php


function __autoload($class) {
    require_once 'lib/' . strtolower($class) . '.class.php';
}


$db = new PDO('sqlite:'.dirname(__FILE__).'/ucd.sqlite');
$router = Router::getRouter();


$router->registerAction(function ($url) {
    global $db;
    if (substr($url, -6) === '_plane') {
        try {
            $plane = new UnicodePlane($url, $db);
        } catch(Exception $e) {
            try {
                $plane = new UnicodePlane(substr($url, 0, -6), $db);
            } catch(Exception $e) {
                return False;
            }
        }
        return $plane;
    }
    return False;
}, function($url, $plane) {
    $view = new View('plane.html');
    echo $view->render(compact('plane'));
})
->registerAction(function ($url) {
    global $db;
    if (substr($url, 0, 2) === 'U+' && ctype_xdigit(substr($url, 2))) {
        try {
            $codepoint = new Codepoint(hexdec(substr($url, 2)), $db);
            $codepoint->getName();
        } catch (Exception $e) {
            return False;
        }
        return $codepoint;
    }
    return False;
}, function ($url, $codepoint) {
    global $db;
    $unidb = new UnicodeDB($db);
    $view = new View('codepoint.html');
    echo $view->render(array(
        'properties' => $unidb->getProperties(),
        'codepoint' => $codepoint));
})
->registerAction(function ($url) {
    global $db;
    if (! preg_match('/[^a-z0-9_-]/', $url)) {
        try {
            $block = new UnicodeBlock($url, $db);
        } catch(Exception $e) {
            return False;
        }
        return $block;
    }
    return False;
}, function($url, $block) {
    $view = new View('block.html');
    echo $view->render(compact('block'));
})
->registerAction(function ($url) {
    return ($url === '' || $url === 'index.php');
}, function ($url, $data) {
    global $db;
    $view = new View('front');
    echo $view->render(array('planes' => UnicodePlane::getAll($db)));
});

$router->registerUrl('Codepoint', function ($object) {
    return sprintf("U+%04X", $object);
})
->registerUrl('UnicodeBlock', function ($object) {
    return str_replace(' ', '_', strtolower($object->getName()));
})
->registerUrl('UnicodePlane', function ($object) {
    $path = str_replace(' ', '_', strtolower($object->getName()));
    if (substr($path, -6) !== '_plane') {
        $path .= '_plane';
    }
    return $plane;
});


$action = $router->getAction();
if ($action !== Null) {
    $action[0]($action[1], $action[2]);
} else {
    header('HTTP/1.0 404 Not Found');
    $view = new View('error404');
    echo $view->render(array(
        'planes' => UnicodePlane::getAll($db)));
}


// __END__
