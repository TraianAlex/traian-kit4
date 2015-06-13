<?php

file_exists('../config.php') ?
        include_once'../config.php' :
        include_once '../../config.php';

class Ajax_method{

/*------------- check username if exist at registration form -----------------------*/
    
    public function check_username($newuser) {

        if (isset($newuser)) {
            $result = Users::get_users($newuser);
            if ($result){
                echo "<span class='taken'>&nbsp;&#x2718; "
                . "Sorry, this username has been taken</span>";
            }else{
                echo "<span class='available'>&nbsp;&#x2714; "
                . "This username is available</span>";
            }
        }
    }
    
    public function check_admins($newuser) {

        if (isset($newuser)) {
            $result = Admins::get_admins($newuser);
            if ($result){
                echo "<span class='taken'>&nbsp;&#x2718; "
                . "Sorry, this username has been taken</span>";
            }else{
                echo "<span class='available'>&nbsp;&#x2714; "
                . "This username is available</span>";
            }
        }
    }
    
    /*-----------delete a comment by jquery ---------------------------------------------*/

    public function delete($id) {

        try {	
            $objDb = new PDO('mysql:host=localhost;dbname=bazain2', 'root', 'aaaa');
            $objDb->exec('SET CHARACTER SET utf8');

            $sql = "DELETE FROM `comentarii` WHERE `id` = ?";
            $statement = $objDb->prepare($sql);

            if ($statement->execute([$id])) {
                
                $sql = "SELECT * FROM `comentarii` ORDER BY `Date` ASC";
                $statement = $objDb->query($sql);
                $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['error' => false, 'posts' => count($posts)]);
            } else {
                    echo json_encode(['error' => true, 'case' => 3]);
            }
        } catch(Exception $e) {
                echo json_encode(['error' => true, 'case' => 2, 'message' => $e->getMessage()]);
        }
    }
}

$ajax = new Ajax_method();
if(isset($_POST['newuser'])){
    $ajax->check_username($_POST['newuser']);
}
if(isset($_POST['newadmin'])){
    $ajax->check_admins($_POST['newadmin']);
}
if(isset($_POST['id'])){
    $ajax->delete($_POST['id']);
}