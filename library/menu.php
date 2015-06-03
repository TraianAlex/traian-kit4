<?php

class menu{

    private static $obj;
    public $mainMenu;
    private $lang;
    public $cssClass;
    
    private function __construct(){
        $this->mainMenu = new item();
    }
    
    public static function init($lang=''){
    
        self::$obj = new menu();
        self::$obj->lang = $lang;
        if(file_exists(PATH_FILE."/tmp//menu{$lang}.tmp")){
            require_once PATH_FILE."/tmp//menu{$lang}.tmp";
            $obj = call_user_func("menuTmp".self::$obj->lang."::getvalue");
            self::$obj = ($obj instanceof menu) ? $obj : $this;
        }
        else
                self::$obj->save();
        return self::$obj;
    }
    
    public function &GetItemByPath($path){
        $path = explode('_', str_ireplace(array("\\",'/'),"_",$path));
        $item = &$this->mainMenu; 
        foreach($path as $index){
            $item = &$item->children[$index];
        }
        return $item;
    }
    
    public function removeItemByPath($path){
        $this->GetItemByPath($path)->remove();
    }
    
    public function addRootItem($title, $href=null, $target=null){
        return $this->mainMenu->addChild($title, $href, $target);
    }
    
    public function Show($menu=null){
        if($menu == null)
            $menu = &$this->mainMenu;
        $out="";
         if(count($menu->children)>0)    
            $out="<ul class='".($menu->depth==0?"{$this->cssClass} ":"submenu ")."ML_{$menu->depth}'>";
        
        if (is_array($menu->children)){
            foreach($menu->children as $item){
                    $class=$item->cssClass==''?'':" class='{$item->cssClass}'";
                    $out.="<li$class id='MLI_{$item->getPath()}'>";
                    $out.=empty($item->href)?"":"<a href='{$item->href}' ".(empty($item->target)?"":"target='{$item->target}'").">";
                    $out.=$item->title;
                    $out.=$this->Show($item);
                    $out.=empty($item->href)?"":"</a>";
                    $out.="</li>";
            }
        }
         if(count($menu->children)>0) 
            $out.="</ul>";
        return $out;
    }
    
    public function ShowSaved(){
        return (file_exists(PATH_FILE."/tmp/data{$this->lang}.tmp")?
               file_get_contents(PATH_FILE."/tmp/data{$this->lang}.tmp"):
               "menu dont create yet, <a href='".SITE_ROOT."/php/menu_generator'>click to create</a>");//dirname(__FILE__)
    }
    
    public function save(){
    
        $m = base64_encode(serialize($this));
        $m ="<?php\nclass menuTmp{$this->lang}{\npublic static function getvalue(){\nreturn @unserialize(base64_decode('$m'));\n}\n}";
         if(!file_exists(PATH_FILE."/tmp/"))mkdir(PATH_FILE."/tmp/");//dirname(__FILE__)
         $data=self::$obj->show();
         if($data!='')
            file_put_contents(PATH_FILE."/tmp/data{$this->lang}.tmp",self::$obj->show());
         return file_put_contents(PATH_FILE."/tmp/menu{$this->lang}.tmp",$m)>0 ?true:false;//dirname(__FILE__)
         
    }
}
