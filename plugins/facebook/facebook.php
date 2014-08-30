<?php

class Facebook extends Plugin {

    private $id;
    private $accessToken;

    public function __construct() {
        parent::__construct();
        $this->__set('accessToken', 'CAACEdEose0cBADj8AdcWwnZAnoJv9tPrR1wmflXbRIdxU5ElnRpRHI8y4FA1yt6VTZALRAGAQ0okJxZBUZALcRVdeyODo2oGmgGYmDKB03nuZCj6jCDqqNaIwMYWhIH7cKXq9ObXK7kW4DTyFrnAwhWCJUgiT0YHJm9xcd66BiwiLoIoCJupjKSt8t8R3RjwZD');
        $this->__set('id', '796220093743337');
        Head::add(get_cur_url('js'));
        Head::add('//connect.facebook.net/en_US/sdk.js');
        Head::add(get_cur_url('facebook.js'));
        Head::addData($this->js());
    }

    public function __get($name) {
        switch ($name) {
            case 'accessToken':
                if ($this->accessToken == NULL) {
                    return $this->get($name);
                }
                return $this->accessToken;
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
            case 'accessToken':
                $this->set($name, $value);
                $this->accessToken = $value;
                return true;
                break;
            case 'id':
                $this->set($name, $value);
                $this->id = $value;
                return true;
                break;
        }
        return false;
    }

    public function js() {
        return "<script type=\"text/javascript\">Facebook.accessToken = \""
             . "" . $this->accessToken . "\";\nFacebook.id = \"" . $this->id
             . "\";\nFacebook.getAlbums();</script>";
    }

}
