<div class="container page-content">
    <div class="row"> 
        <?=URL::create_crumbs();?>
    </div>
    <div class="row"><?php
    if($arrData){
        foreach ($arrData as $row){?>
                <h3><?=$_SESSION['user']?> profile</h3>
                 <!--p><img src='<?php //$pic?>' class='user_pic'></p-->
                 <ul><li>Name <strong><?php //$row['firstname']?> <?php //$row['lastname']?></strong></li>
                     <br>
                     <li>Email <strong><?=ProtectEmail($row->email)?></strong></li>
                     <br>
                     <li>City <strong><?php //$row['city']?></strong></li>
                 </ul><br><br>
                    <!--a class='button' href='<?php //SITE_ROOT?>/messages/messages/<?php //$view?>'>View <?php //$vb[1]?> messages</a-->
                 
                <p><a href='<?=SITE_ROOT?>/users/personal_data'>change data</a><br>
                <p><a href='<?=SITE_ROOT?>/users/password'>change password</a><br><?php
        }        
    }else{
        echo "The user data is missing";
    }?>
        <p><a href='<?=SITE_ROOT?>/users/profile'>Refresh</a>
        <p><a href='javascript:history.go(-1);'>Back</a>

    </div>
</div>
