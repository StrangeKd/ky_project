<?php
session_start();
Autoloader::register();

function displayArticleElements()
{
    $displayArticle = new RenderArticle();
    $currentID = isset($_GET['id']) ? $_GET['id'] : null;
    $displayArticle->renderArticles($currentID);
    if (isset($currentID)) {
        echo($displayArticle->articlePage);
    } else {
        for ($i = 0; $i < 5; $i++) {
            if (array_key_exists($i, $displayArticle->articleCards)) {
                echo($displayArticle->articleCards[$i]);
            }
        }
    }
}
