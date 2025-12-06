<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
switch ($action) {
    case 'post-index':
    case null:
        $controller = new \App\Controller\PostController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view = $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-edit':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->editAction((int)$_REQUEST['id'], $_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-show':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->showAction((int)$_REQUEST['id'], $templating, $router);
        break;
    case 'post-delete':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->deleteAction((int)$_REQUEST['id'], $router);
        break;

    // Person routes (PersonController renders templates directly -> no string return)
    case 'people-index':
        $controller = new \App\Controller\PersonController();
        $controller->indexAction();
        $view = null;
        break;
    case 'people-create':
        $controller = new \App\Controller\PersonController();
        $controller->createAction();
        $view = null;
        break;
    case 'people-edit':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PersonController();
        $controller->editAction();
        $view = null;
        break;
    case 'people-show':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PersonController();
        $controller->showAction();
        $view = null;
        break;
    case 'people-delete':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PersonController();
        $controller->deleteAction();
        $view = null;
        break;

    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
