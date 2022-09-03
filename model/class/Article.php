<?php
class Article extends Form
{
    const TYPE = 'article';
    protected $query;
    protected $querySize;

    public function __construct(?array $data = null, ?array $input = null)
    {
        parent::__construct($data, $input);
    }

    public function init()
    {
        parent::init();
        if ($this->success() === true) {
            $this->logMsg('Article successfully posted');
        } else {
            $form = $this->generateForm();
            return $form;
        }
    }

    private function success()
    {
        foreach($this->input as $key => $value) {
            if($value['type'] === 'file') {
                if(isset($_FILES[$value['name']])) {
                    if($_FILES[$value['name']]['error'] === 0) {
                        if ($this->saveToDb() === true) {
                            $this->clearData();
                            return true;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }

    private function saveToDb()
    {
        if (!empty($this->data) && !empty($this->input['article_title']['value'])) {
            $title = isset($this->input['article_title']['value']) ? $this->input['article_title']['value'] : null;
            $content = isset($this->input['article_content']['value']) ? $this->input['article_content']['value'] : null;
            $artist = isset($this->input['article_artist']['value']) ? $this->input['article_artist']['value'] : null;
            $img = !empty($this->imgUrl) ? $this->imgUrl : null;
            $publication = $this->currentDatetime;
            $params = [
                'type' => self::TYPE,
                'title' => $title,
                'content' => $content,
                'artist' => $artist,
                'img' => $img,
                'publication_date' => $publication
            ];
            $this->prepare('INSERT INTO posts (type, title, content, artist, img, publication_date) VALUE (:type, :title, :content, :artist, :img, :publication_date)', $params, true);
            return true;
        }
    }

    protected function queryData(?string $id = null)
    {
        if (isset($id)) {
            $params = [
                'type' => 'article',
                'id' => $id
            ];
            $this->query = $this->prepare('SELECT title, content, artist, img FROM posts WHERE type = :type AND id = :id ORDER BY publication_date DESC', $params, true);
        } else {
            $params = [
                'type' => 'article'
            ];
            $this->query = $this->prepare('SELECT id, title, content, artist, img FROM posts WHERE type = :type ORDER BY publication_date DESC', $params);
            $this->querySize = count($this->query);
        };
    }
}
