<?php
 
if($row){?>
    <h3><?=$view?> profile</h3>
     <!--p><img src='<?php //$pic?>' class='user_pic'></p-->
     <ul><li>Name <strong><?php //$row['firstname']?> <?php //$row['lastname']?></strong></li>
         <br>
         <li>Email <strong><?=ProtectEmail($row['email'])?></strong></li>
         <br>
         <li>City <strong><?php //$row['city']?></strong></li>
     </ul><br><br>
        <!--a class='button' href='<?php //SITE_ROOT?>/messages/messages/<?php //$view?>'>View <?php //$vb[1]?> messages</a--><?php
}else{
    echo "The user data is missing";
}