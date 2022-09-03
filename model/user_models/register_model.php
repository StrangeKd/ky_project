<?php
session_start();
Autoloader::register();
if(isset($_SESSION['username'])) {
    header('Location: index.php?page=account');
    exit();
}

date_default_timezone_set('Europe/Paris');
$page = $_GET['page'];

$input = [
    'username' => [
        'id' => 'username',
        'label' => 'Username : ',
        'maxlength' => 50,
        'minlength' => 3,
        'placeholder' => 'KY username',
        'properties' => ' required autofocus'
    ],
    'email' => [
        'id' => 'email',
        'type' => 'email',
        'label' => 'Email : ',
        'maxlength' => 80,
        'properties' => ' required',
        'labelClass' => 'log-label email-register',
    ],
    'password' => [
        'id' => 'password',
        'type' => 'password',
        'label' => 'Password : ',
        'minlength' => 6,
        'properties' => ' required',
        'labelClass' => 'log-label register-password'
    ],
    'submit' => [
        'type' => 'submit',
        'value' => 'Create account',
        'class' => 'log-btn register-btn'
    ]
];
$registerForm = new User($_POST, $input, $page);
