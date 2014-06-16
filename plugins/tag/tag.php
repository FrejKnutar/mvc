<?php
abstract class Tag
{
    private static $stack = array();
    private $attributes = array();
    protected $tag;
    
    public function __construct() {
        $this->tag = get_class($this);
    }
    
    public final static function createTag($tagName) {
        if (!class_exists($tagName)) {
            throw new Exception("Tag '$tagName' does not exist");
        }
        $refClass = new ReflectionClass($tagName);
        if ($refClass->isAbstract() || $refClass->isInterface()) {
            throw new Exception("Unable to create instance of '$tagName'");
        }
        $obj = $refClass->newInstance();
        if (!is_a($obj, get_class())) {
            throw new Exception("Class '$tagName' is not a Tag");
        }
        self::$stack[] = $obj;
        $obj->doStartTag();
        
    }
    
    public final static function removeTag($tagName) {
        for ($i = count(self::$stack) - 1; $i >= 0; $i++) {
            $tag = self::$stack[$i];
            if (get_class($tag) == $tagName) {
                $tag->doEndTag();
                unset(self::$stack[$i]);
                return true;
            }
        }
        throw new OutOfBoundsException("The tag with name '$tagName' has not been created");
    }
    
    public function __set(String $name, String $value) {
        $this->attributes[$name] = $value;
    }
    
    public function __get($name) {
        return $this->attributes[$name]?:NULL;
    }
    
    public function doStartTag() {}
    
    public function doEndTag() {}
}

function £($tagName) {
    if ($tagName{0} == '/') {
        Tag::removeTag(substr($tagName, 1));
    } else {
        Tag::createTag($tagName);
    }
}