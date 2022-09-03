<?php
class RenderArticle extends Article
{
    public $articleCards;
    public $articlePage;
    private $classArray = [
        'home-article-card' => 'home-article-card',
        'home-article-title' => 'home-article-title',
        'home-article-img' => 'home-article-img',
        'article-card' => 'article-card',
        'article-img' => 'article-img'
    ];

    public function renderArticles(?string $id = null)
    {
        if (isset($id)) {
            $this->queryData($id);
            $this->generateArticlePage($id);
        } else {
            $this->queryData();
            $this->generateArticle();
        }
    }

    private function generateArticle()
    {
        if ($this->query !== false) {
            $data = $this->query;
            $index = $this->querySize;
            $this->articleCards = [];
            $key = 0;
            for ($i = 1; $i <= $index; $i++) {
                array_push(
                    $this->articleCards,
                    '<div class="' . $this->classArray['home-article-card'] . '">
                        <a href="index.php?page=articles&id=' . $data[$key]["id"] . '">
                        <h2 class="' . $this->classArray['home-article-title'] . '">' . $data[$key]["title"] . '</h2>
                        <div class="' . $this->classArray['home-article-img'] . '">
                            <img src="' . $data[$key]["img"] . '" alt="Article image">   
                        </div>
                    </a>
                </div>'
                );
                $key++;
            };
            return ($this->articleCards);
        }
    }

    private function generateArticlePage(string $id)
    {
        if (empty($id) || $this->query === false) {
            header('Location: index.php?page=articles');
            exit();
        }
        if ($this->query !== false) {
            $data = $this->query;
            $this->articlePage = (
                '<div class="' . $this->classArray['article-card'] . '">
                    <div class="' . $this->classArray['article-img'] . '">
                        <img src="' . $data["img"] . '" alt="Article image">
                    </div>
                    <h1>' . $data["title"] . '</h1>
                    <p>' . $data["content"] . '</p>
                </div>');
            return ($this->articlePage);
        }
    }
}
