<?php

class item{

    public $title;
    public $href = "";
    public $depth = 0;
    public $target = "";
    public $parent;
    PUBLIC $ID;
    public $cssClass;
    public $children = array();
    
    public function addChild($title, $href=null, $target=null){
    
        $Child = new item;
        $Child->title = $title;
        $Child->href = $href;
        $Child->target = $target;
        $Child->depth = $this->depth+1;
        $Child->parent = &$this;
        $Child->ID = uniqid();
        $this->children[] = $Child;
        return $Child;
    }
    
    public function remove(){
        unset(  $this->parent->children[array_search($this, $this->parent->children)]);
        $this->parent->children = array_values($this->parent->children);
    }
    
    public function getIndex(){
        return array_search($this,$this->parent->children);
    }
    
    public function getPath(){
        $p = &$this;
        $path = "";
        while($p->parent != null){
            $path = $p->getIndex() . "_" . $path;
            $p = &$p->parent;
        }
        return rtrim($path,'_');
    }
}