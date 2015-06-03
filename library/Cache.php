<?php
/**
 * Cache class
 * @version  1.0
 * @update   2003-04-29 12:12:37
 */

class Cache {
    /**
     * url wanta cached
     *
     * @var string
     */
    private $cached_file;
    /**
     * path to the cache save
     *
     * @var string
     */
    private $cached_path;
    /**
     * cached limit time
     *
     * @var string
     */
    private $cached_time;
    /**
     * expire time
     *
     * @var string
     */
    private $cached_modtime;
    /**
     * Construct function
     *
     * @access public
     * @param string $path Cached path
     * @param int $time Cached time
     * @return void
     */
    public function __construct($path='cache/',$time=120) {
        global $HTTP_SERVER_VARS;
        $query_str = preg_replace('/(&submit\.[x|y]=[0-9]+)+$/','',$HTTP_SERVER_VARS['REQUEST_URI']);
        $this->cached_file = md5($query_str).'.cache';
        $this->cached_path = $path;
        $this->cached_time = $time * 1;//3600 test
        if (is_dir($this->cached_path)===false) {
            mkdir($this->cached_path,0777);
        }
        if (file_exists($this->cached_path.$this->cached_file)) {
            $this->cached_modtime = date(time()-filemtime($this->cached_path.$this->cached_file));
        }
    }

    /**
     * Start the cache
     *
     * @access public
     */
    public function start() {
        global $HTTP_GET_VARS;
        if ( ($HTTP_GET_VARS['update']!="") || (!file_exists($this->cached_path.$this->cached_file)) || ($this->cached_modtime > $this->cached_time) ) {
            ob_start(); 
        } else {
            readfile($this->cached_path.$this->cached_file);
            exit();
        }
    }

    /**
     * End the cache
     *
     * @access public
     */
    public function end() {
        global $HTTP_GET_VARS;
        if ( ($HTTP_GET_VARS['update']!="") || (!file_exists($this->cached_path.$this->cached_file)) || ($this->cached_modtime > $this->cached_time) ) {
            $contents = ob_get_contents();
            ob_end_clean(); 
            $HTTP_GET_VARS['update']!="" ? chmod($this->cached_path.$this->cached_file,0777) : '';
            $fp = fopen($this->cached_path.$this->cached_file,'w'); 
            fputs($fp,$contents); 
            fclose($fp); 
            echo $contents;
        }
    }

    /**
     * Flush all cache
     *
     * @access public
     */
    private function flush() {
        if (function_exists('exec')) {
            if (strpos(strtoupper(PHP_OS),'WIN') !== false) {
                $cmd = 'del /s '.str_replace('/','\\',$this->cached_path).'*.cache';
            } else {
                $cmd = 'rm -rf '.$ADODB_CACHE_DIR.'/*.cache'; 
            }
            exec($cmd);
        } else {
            $d = dir($this->cached_path); 
            while ($entry = $d->read()) {
                $modtime = date(time()-filemtime($this->cached_path.$entry));
                // if (($entry != ".") && ($entry != "..") && ($modtime > $this->cached_time)) { 
                if (($entry != ".") && ($entry != "..")) { 
                    chmod($this->cached_path.$entry,0777);
                    unlink($this->cached_path.$entry);
                }
            }
            $d->close(); 
        }
        return;
    }

} //End Class