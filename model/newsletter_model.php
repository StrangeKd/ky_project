<?php
$input = [
    'news-email' => [
        'label' => 'Email :',
        'id' => 'news-email',
        'placeholder' => 'Stay tuned',
        'divClass' => 'newsletter-input-container',
        'class' => 'newsletter-input'
    ],
    'submit' => [
        'type' => 'submit',
        'value' => 'Go',
        'class' => 'newsletter-btn'
    ]
];

$newsletterForm = new User($_POST, $input);
