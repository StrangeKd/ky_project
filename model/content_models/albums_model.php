<?php
session_start();
Autoloader::register();

function displayAlbumElements() {
    $displayAlbum = new RenderAlbum();
    $displayAlbum->renderAlbums();
    for ($i = 0; $i < 12; $i++) {
        if (array_key_exists($i, $displayAlbum->albumCards)) {
            echo($displayAlbum->albumCards[$i]);
        }
    }
}