<?php
function __autoload($classname){
    include_once("controllers/$classname.php");
}

$action = 'action_';
$action .= (isset($_GET['act'])) ? $_GET['act'] : 'index';

switch ($_GET['c'])
{
    case 'articles':
        $controller = new C_Articles();
        break;
    case 'editor':
        $controller = new C_ConsoleEditor();
        break;
    default:
        $controller = new C_Articles();
}

header('Content-type: text/html; charset=utf-8');

$controller->request($action);