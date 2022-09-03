<?php
session_start();
if(!isset($_SESSION['access_admin'])) {
    header('Location: index.php');
    exit();
} else if($_SESSION['access_admin'] === false) {
    header('Location: index.php');
    exit();
}
Autoloader::register();

date_default_timezone_set('Europe/Paris');

$inputAlbum = [
    'album_title' => [
        'label' => 'Album title :   ',
        'maxlength' => 100,
        'properties' => ' required autofocus',
        'labelClass' => 'back-label-input',
        'class' => 'back-form-input',
        'divClass' => 'back-input-container'
    ],
    'album_artist' => [
        'label' => 'Artist name : ',
        'maxlength' => 100,
        'class' => 'back-input-form',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container'
    ],
    'album_img' => [
        'type' => 'file',
        'label' => 'Cover : ',
        'properties' => ' required',
        'class' => 'log-input file-input',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container back-file-container'
    ],
    'album_release' => [
        'type' => 'date',
        'label' => 'Release : ',
        'properties' => ' required',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container'
    ],
    'album_link' => [
        'label' => 'Album link : ',
        'class' => 'back-input-form',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container'
    ],
    'confirm' => [
        'type' => 'submit',
        'value' => 'Confirm',
        'class' => 'back-form-btn'
    ]
];
$inputArticle = [
    'article_title' => [
        'label' => 'Article title :   ',
        'maxlength' => 100,
        'properties' => ' required',
        'class' => 'back-input-form',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container'
    ],
    'article_artist' => [
        'label' => 'Artist about the article : ',
        'maxlength' => 100,
        'class' => 'back-input-form',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container',
    ],
    'article_img' => [
        'type' => 'file',
        'label' => 'Article image : ',
        'properties' => ' required',
        'class' => 'log-input file-input',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container back-file-container',
    ],
    'article_content' => [
        'type' => 'textarea',
        'label' => 'Article content : ',
        'properties' => ' required',
        'rows' => 5,
        'cols' => 25,
        'class' => 'back-form-textarea',
        'labelClass' => 'back-label-input',
        'divClass' => 'back-input-container'
    ],
    'confirm' => [
        'type' => 'submit',
        'value' => 'Confirm',
        'class' => 'back-form-btn'
    ]
];

$albumForm = new Album($_POST, $inputAlbum);
$articleForm = new Article($_POST, $inputArticle);
