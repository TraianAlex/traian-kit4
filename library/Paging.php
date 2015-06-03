<?php
/**
* @package php-pdo
* pagination.php
* Paging file
* Author: Traian Alexandru <victor_traian@yahoo.com>
* @version 1.0 2014-01-07
* @copyright Copyright (c) 2014, All Rights Reserved
* @license GNU General Public License
* @since Since Release 1.0
* Description: paginate mysql data with number page on the top, previous,
* current and Next on the bottom
**/
class Paging extends Base{

      private $per_page;
      private $page;//url
      public  $total_pages;
      private $num_of_rows;

    public function __construct($fields, $table, $per_page){
        $this->per_page = $per_page;
        $this->num_of_rows = $this->rowCount($fields, $table);
        $this->total_pages = ceil($this->num_of_rows / $this->per_page);
        $this->page = $this->setThisPage();
        $this->start_from = ($this->page - 1) * $this->per_page;
        $this->limit = "{$this->start_from},{$this->per_page}";
    }

    private function rowCount($fields, $table){

        $result = $this->select($fields, $table);
	return $result->rowCount();
    }

    public function getResult($fields, $table, $order){

        $result = $this->select($fields, $table, null, null, $order, $this->limit);
        return $result;
    }

    private function setThisPage() {
        
       if ($this->total_pages == 0) {
           $this->page = 1;
           echo "No entries....";
       } elseif (isset($_GET['url'])) {
           $this->page = filter_input(INPUT_GET, 'url',FILTER_SANITIZE_NUMBER_INT);
           if($this->page > $this->total_pages || $this->page === 0 || $this->page == 0){
               $this->page = 1;
           }
       } else {
           $this->page = 1;
       }
       return $this->page;
    }

    public function count_page(){
        
        $count = '<p id="picCount">[';
        $count .= $this->start_from + 1;
        if ($this->start_from + 1 < $this->num_of_rows) {
            $count .= ' to ';
            if ($this->start_from + $this->per_page < $this->num_of_rows) {
                $count .= $this->start_from + $this->per_page;
            } else {
                $count .= $this->num_of_rows;
            }
        }
        $count .= " of $this->num_of_rows";
        $count .= ']</p>';
        return $count;
    }

    public function prev($class, $page, $prev="Previous") {
        
      if($this->page > 1) {
        $pr = $this->page - 1;
        return "<div class='boxx'><a class='prev' href=".SITE_ROOT."/{$class}/{$page}/{$pr}>{$prev}</a></div> ";
      }
    }

    public function next($class, $page, $next="Next"){
        
       if($this->page < $this->total_pages) {
         $nx = $this->page + 1;
         return "<div class='boxx'><a class='next' href=".SITE_ROOT."/{$class}/{$page}/{$nx}>{$next}</a></div>";
       }
    }

    public function curent($class, $page){
        
        for($pages = 1; $pages <= $this->total_pages; $pages++){
            if(($this->page) == $pages){
                echo " <div class='boxx'><b>{$this->page}</b></div> ";
            }else{
                echo "<div class='boxx'><a class='curent' href=".SITE_ROOT."/{$class}/{$page}/{$pages}>{$pages}</a></div> ";
            }
        }
    }
/*
 * put all together from above //me
 */
    public function number_pages($class, $page) {

    if($this->total_pages === 1){ echo '';}
    else{
        echo '<div class="pagination">';
        echo $this->prev($class, $page, "&laquo;");
        $this->curent($class, $page);
        echo $this->next($class, $page, "&raquo;");
        echo '</div>';
    }
    }
 
    /*--------------------------------------------------------------------------------*/

    public function getResult2($fields, $table, $order, $like){
        
        $result = $this->select($fields, $table, null, null, $order, $this->limit, $like);
        return $result;
    }

    public function prev2($h, $adr, $page, $prev="Previous") {
        
      if($this->page > 1) {
        $pr = $this->page - 1;
        //return "<a class='prev' href=".SITE_ROOT."/{$page}/{$pr}>{$prev}</a> ";
        return URL::xlink2('class="prev"', $h, $adr, $page, $pr, $prev);
    }
    }

    public function next2($h, $adr, $page, $next="Next"){
        
       if($this->page < $this->total_pages) {
         $nx = $this->page + 1;
         //return "<a class='next' href=".SITE_ROOT."/{$page}/{$nx}>{$next}</a>";
         return URL::xlink2('class="next"', $h, $adr, $page, $nx, $next);
    }
    }

    public function curent2($h, $adr, $page){
        for($pages = 1; $pages <= $this->total_pages; $pages++){
            if(($this->page) == $pages){
                echo " <b>{$this->page}</b> ";
            }else{
                //echo "<a href=".SITE_ROOT."/{$page}/{$pages}>{$pages}</a> ";
                echo URL::xlink2('class="next"', $h, $adr, $page, $pages, $pages);
            }
        }
    }

}