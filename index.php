<?php
$route = [
    'register' => 'user_controllers/register',
    'login' => 'user_controllers/login',
    'logout' => 'user_controllers/logout',
    'account' => 'user_controllers/account',
    'albums' => 'content_controllers/albums',
    'articles' => 'content_controllers/articles',
    'artists' => 'content_controllers/artists',
    'back' => 'back',
    '404' => '404'
];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if (isset($route[$page])) {
        require './controller/' . $route[$page] . '_controller.php';
    } else {
        require './controller/' . $route['404'] . '_controller.php';
    }
} else {
    require './controller/home_controller.php';
}
