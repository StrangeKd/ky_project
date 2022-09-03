<?php
class Album extends Form
{

    const TYPE = 'album';
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
            $this->logMsg('Album successfully posted');
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
        if (!empty($this->data) && !empty($this->input['album_title']['value'])) {
            $title = isset($this->input['album_title']['value']) ? $this->input['album_title']['value'] : null;
            $artist = isset($this->input['album_artist']['value']) ? $this->input['album_artist']['value'] : null;
            $url = isset($this->input['album_link']['value']) ? $this->input['album_link']['value'] : null;
            $img = !empty($this->imgUrl) ? $this->imgUrl : null;
            $release = isset($this->input['album_release']['value']) ? $this->input['album_release']['value'] : null;
            $publication = $this->currentDatetime;
            $params = [
                'type' => self::TYPE,
                'title' => $title,
                'artist' => $artist,
                'url' => $url,
                'img' => $img,
                'release_date' => $release,
                'publication_date' => $publication
            ];
            $this->prepare('INSERT INTO posts (type, title, artist, url, img, release_date, publication_date) VALUE (:type, :title, :artist, :url, :img, :release_date, :publication_date)', $params, true);
            return true;
        }
    }

    protected function queryData()
    {
        $params = [
            'type' => 'album'
        ];
        $this->query = $this->prepare('SELECT title, artist, url, img, release_date FROM posts WHERE type = :type ORDER BY publication_date DESC', $params);
        $this->querySize = count($this->query);
    }
}
