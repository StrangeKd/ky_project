<?php
class RenderAlbum extends Album
{
    private $classArray = [
        'card' => 'card album',
        'card-link' => 'card-link',
        'card-img-container' => 'card-img-container',
        'card-img' => 'card-img',
        'card-text' => 'card-text',
        'target' => 'target="_blank"'
    ];
    public $albumCards;

    public function renderAlbums(bool $isHome = false)
    {
        $this->queryData();
        $this->generateAlbums($isHome);
    }

    private function generateAlbums(bool $class)
    {
        if ($this->query !== false) {
            $data = $this->query;
            $index = $this->querySize;
            $this->albumCards = [];
            $key = 0;
            $cardClass = $class ? 'card home-album' : 'card album';
            for ($i = 1; $i <= $index; $i++) {
                array_push(
                    $this->albumCards,
                    '<div class="' . $cardClass . $i . '">
                    <a class="' . $this->classArray["card-link"] . '" href="' . $data[$key]["url"] . '" ' . $this->classArray["target"] . '>
                        <div class="' . $this->classArray["card-img-container"] . '">
                            <img class="' . $this->classArray["card-img"] . '" src="' . $data[$key]["img"] . '" alt="' . $data[$key]["title"] . ' cover">
                        </div>
                        <div class="' . $this->classArray["card-text"] . '">
                            <h3>' . $data[$key]["title"] . '</h3>
                            <p>' . $data[$key]["artist"] . '</p>
                        </div>
                    </a>
                </div>'
                );
                $key++;
            };
            return ($this->albumCards);
        }
    }
}
