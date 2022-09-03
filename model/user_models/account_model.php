<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php?page=register');
    exit();
}
Autoloader::register();

$page = $_GET['page'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

$inputModify = [
    'current_password' => [
        'type' => 'password',
        'label' => 'Type your password :',
        'placeholder' => 'Current password',
        'properties' => ' required autofocus'
    ],
    'new_password1' => [
        'type' => 'password',
        'label' => 'Your new password :',
        'placeholder' => 'New password',
        'properties' => ' required'
    ],
    'new_password2' => [
        'type' => 'password',
        'label' => 'Retype your new password :',
        'placeholder' => 'New password',
        'properties' => ' required'
    ],
    'submit' => [
        'type' => 'submit',
        'value' => 'Save changes'
    ]
];

$inputDelete = [
    'delete' => [
        'label' => 'Are you sure you want to delete your account ?',
        'placeholder' => 'To confirm, type DELETE',
        'properties' => ' required autofocus'
    ],
    'submit' => [
        'type' => 'submit',
        'value' => "I'm sure"
    ]
];


function renderModifyForm()
{
    global $page;
    global $action;
    global $inputModify;
    $modifyPassword = new User($_POST, $inputModify, $page, $action);
    if ($_GET['action'] === 'modify') {
        echo($modifyPassword->init() . isset($modifyPassword->msg) ? $modifyPassword->msg : "");
    }
}

function renderDeleteForm()
{
    global $page;
    global $action;
    global $inputDelete;
    $deleteAccount = new User($_POST, $inputDelete, $page, $action);
    if ($_GET['action'] === 'delete') {
        echo($deleteAccount->init() . isset($deleteAccount->msg) ? $deleteAccount->msg : "");
    }
}
