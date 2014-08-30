<?php

class Spotify extends Plugin {

    private $id;

    public function __construct() {
        parent::__construct();
        $this->__set('id', '4aaBqtuCgkgQce4h3lZKDc');
    }

    public function __get($name) {
        switch ($name) {
            case 'id':
                if ($this->id == NULL) {
                    return $this->get($name);
                }
                return $this->id;
        }
        return false;
    }

    public function __set($name, $value) {
        if (!is_string($value)) {
            return false;
        }
        switch ($name) {
            case 'id':
                $this->set($name, $value);
                $this->id = $value;
                return true;
                break;
        }
        return false;
    }

    public function createPlayer(
            $width = 640, $height = 80, $frameborder = 0, $allowtransparency = true) {
        return "<iframe src=\"https://embed.spotify.com/?uri=spotify:artist:"
        . $this->id . "\" width=\"$width\" height=\"$height\" frameborder=\""
        . "$frameborder\" allowtransparency=\"" 
        . ($allowtransparency ? 'true' : 'false') . "\"></iframe>";
    }
    
}
