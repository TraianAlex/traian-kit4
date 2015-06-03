<div class="container page-content">
    <div class="row"> 
        <?=URL::create_crumbs();?>
    </div>
    <div class="row"><?php
    if($arrData){
        foreach ($arrData as $row){?>
                <h3>Profile</h3>
                <p><img src='<?=SESSIONS::get('pic')?>' class='user_pic'></p>
                 <ul><li>Name <strong><?=$row->fld_user_name?></strong></li>
                    <br>
                    <li>Email <strong><?=ProtectEmail($row->fld_user_email)?></strong></li>
                 </ul><br><br>
                    <!--a class='button' href='<?php //SITE_ROOT?>/messages/messages/<?php //$view?>'>View <?php //$vb[1]?> messages</a--><?php
        }      
    }else{
        echo "The user data is missing";
    }?>
        <p><a href='<?=SITE_ROOT?>/users/profile'>Refresh</a>
        <p><a href='javascript:history.go(-1);'>Back</a>

    </div>
</div>