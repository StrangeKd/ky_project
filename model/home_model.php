<?php
session_start();
Autoloader::register();

function renderHomeAlbums() {
    $displayAlbum = new RenderAlbum();
    $displayAlbum->renderAlbums(true);
    for ($i = 0; $i < 4; $i++) {
        if (array_key_exists($i, $displayAlbum->albumCards)) {
            echo($displayAlbum->albumCards[$i]);
        }
    }
}

function renderHomeArticles() {
    $displayArticle = new RenderArticle();
    $displayArticle->renderArticles();
    for ($i = 0; $i < 3; $i++) {
        if (array_key_exists($i, $displayArticle->articleCards)) {
            echo($displayArticle->articleCards[$i]);
        }
    }
}