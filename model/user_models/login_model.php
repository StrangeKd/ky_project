<?php
session_start();
Autoloader::register();
if(isset($_SESSION['username'])) {
    header('Location: index.php?page=account');
    exit();
}

$page = $_GET['page'];
$input = [
    'log' => [
        'id' => 'log',
        'label' => 'Username or Email: ',
        'maxlength' => 50,
        'minlength' => 3,
        'placeholder' => 'KY username or email address',
        'properties' => ' required autofocus',
    ],
    'password' => [
        'id' => 'password',
        'type' => 'password',
        'label' => 'Password : ',
        'minlength' => 4,
        'properties' => ' required',
        'labelClass' => 'log-label login-password'
    ],
    'submit' => [
        'type' => 'submit',
        'value' => 'Login'
    ]
];

$loginForm = new User($_POST, $input, $page);
