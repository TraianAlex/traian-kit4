<div class="container page-content">
    <div class="row">
        <?=URL::create_crumbs()?>
    </div> 
    <div class="row">
        <br><br>
        <form name="formular" action="<?=SITE_ROOT?>/users/change_pass/<?=T?>" method="post">
        <fieldset><p><strong>Old Password: </strong>&nbsp;
        <input type="password" name="old_pwd" autofocus onFocus="this.value=\'\'">
        <p><strong>New Password: </strong><input type="password" name="pwd">
        <p><strong>Confirm: </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="password" name="repwd">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="password" id="Trimite" value="Change">
        <br><br><a href="javascript:history.go(-1);"><strong>Back</strong></a>
        </fieldset><?=CSRF::tokenize()?>
        </form><?=Errors::show_errors()?>

    </div>
</div>