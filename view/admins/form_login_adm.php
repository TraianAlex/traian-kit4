<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?=SITE_ROOT?>/inc/sign_in_forgot2_files/formoid1/formoid-solid-light-green.css" type="text/css" />

<div class="container page-content">
    <div class="row">
<?=Errors::show_errors()?>
<form name="Login" action="<?=SITE_ROOT?>/admins/login_adm/<?=T?>" class="formoid-solid-light-green"  method="post">
      <input type="text" name="user" required="required" placeholder="Username" autocorrect="off" onFocus="this.value=\"\"" pattern="^[A-Za-z0-9\@_.\'-]+$"/>
      <input type="password" name="pwd" required="required" placeholder="Password" onFocus="this.value=\"\"" autocorrect="off"/><!--span class="icon-place"></span--><!--/div--><!--/div-->
    <input type="submit" style="width:6em;" name="submit" value="Sign In" />
        <?=CSRF::tokenize()?>
</form>

    </div>
</div>