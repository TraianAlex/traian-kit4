<div class="container page-content">
    <div class="row">
        <?=URL::create_crumbs();?>
    </div>
    <div class="row"><?php
if($arrData){
    foreach($arrData as $user){
        $_SESSION['temp_email'] = $user->email?>
            <p>
                <!--img src="<?php //$pic?>" alt="" class="user_pic"--><?php //$delete?>
            <form name="formular" action="<?=SITE_ROOT?>/users/update_data/<?=T?>" method="post">
            <fieldset><p><strong>Firstname </strong>
            <input type="text" name="firstname" value="<?php //$user['firstname']?>"><p><strong>Lastname </strong>
            <input type="text" name="lastname" value="<?php //$user['lastname']?>">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Email </strong>
            <input type="text" name="p_email" value="<?=$user->email?>">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>City </strong>
            <input type="text" name="city" value="<?php //$user['city']?>">
            <input name="change_data" type="submit" value="Change data">
            </fieldset><?=CSRF::tokenize()?>
            </form>
        </div>
        <div class="row">
            <form name='formular' action='<?=SITE_ROOT?>/users/personal_data/<?=T?>' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='MAX_FILE_SIZE' value='3000000'>Upload a picture:
            <input type='file' name='user_pic' size='30'>
            <input name='add_pic' type='submit' id='' value='Add'>
                    <?=CSRF::tokenize()?>
            </form>
                    <?=Errors::show_errors()?>
            <br><br><a href='javascript:history.go(-1);'><strong>Back</strong></a><?php
    }
}?>
 
    </div>
</div>