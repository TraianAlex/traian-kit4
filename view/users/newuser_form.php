<?php isset($_SESSION['reg']) ? $p = $_SESSION['reg'] : $p = NULL;?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?=SITE_ROOT?>/inc/newuser_form_files/formoid1/formoid-solid-green.css" type="text/css" />

<div class="container page-content">
    <div class="row">
        <?=URL::create_crumbs();?>
    </div>
    <div class="row">

<form id="signup_form" name="signup_form" method="post" action="<?=SITE_ROOT?>/users/register/<?=T?>" class="formoid-solid-green" style="background-color:#FFFFFF;font-size:14px;font-family:'Roboto',Arial,Helvetica,sans-serif;color:#34495E;max-width:620px;min-width:150px">
    <div class="title"><h2>Registration form</h2></div>
    <?=Errors::show_errors()?>

	<div class="element-input">
            <label class="title"><span class="required">*</span></label>
            <div class="item-cont">
<input class="large" type="text" name="newuser" value="<?=$p['newuser']?>" placeholder="username" onfocus="this.select()" pattern="^[A-Za-z_-@.\']+[0-9]{0,9}$" autofocus onBlur="checkUser(this)" required="required">
<span class="icon-place"></span></div></div>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="info"></span>

	<div class="element-password">
            <label class="title"><span class="required">*</span></label>
            <div class="item-cont">
<input type="password" id="password" name="pwd" class="large" value="<?=$p['pwd']?>" placeholder="password" required="required">
<span class="icon-place"></span></div></div>
<div class="password-meter">
          <div class="password-meter-message"></div>
          <div class="password-meter-bg">
            <div class="password-meter-bar"></div>
          </div>
</div>
        <div class="element-password">
            <label class="title"></label>
            <div class="item-cont">
<input class="large" type="password" id="confirm_password" name="repwd" value="<?=$p['repwd']?>" placeholder="retype password" required="required">
<span class="icon-place"></span>
            </div>
        </div>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter your email address as you will be sent a message confirming your registration

	<div class="element-email">
            <label class="title"><span class="required">*</span></label>
            <div class="item-cont">
<input class="large" type="email" placeholder="Email" name="email" value="<?=$p['email']?>" onblur="verificaemail(this)" onfocus="this.select()" pattern="^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+.([a-zA-Z])+([a-zA-Z])+/)" required="required" />
<span class="icon-place"></span>
            </div>
          </div>

<?=Captcha::create_captcha1()?>

<div class="submit">
<input type="submit" name="add_new_user" value="Register"/>
</div><?=CSRF::tokenize()?>
</form>

    </div>
</div>