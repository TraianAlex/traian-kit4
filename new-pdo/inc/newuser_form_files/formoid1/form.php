<?php

define('EMAIL_FOR_REPORTS', 'victor_traian@yahoo.com');
define('RECAPTCHA_PRIVATE_KEY', '6LfD7O0SAAAAALj6ih-5pT3jg-ZKNPOldYZ59lgD');
define('FINISH_URI', 'http://');
define('FINISH_ACTION', 'message');
define('FINISH_MESSAGE', 'Thanks for filling out my form!');
define('UPLOAD_ALLOWED_FILE_TYPES', 'doc, docx, xls, csv, txt, rtf, html, zip, jpg, jpeg, png, gif');

require_once str_replace('\\', '/', __DIR__) . '/handler.php';

?>

<?php if (frmd_message()): ?>
<link rel="stylesheet" href="<?=dirname($form_path)?>/formoid-solid-green.css" type="text/css" />
<span class="alert alert-success"><?=FINISH_MESSAGE;?></span>
<?php else: ?>
<!-- Start Formoid form-->
<link rel="stylesheet" href="<?=dirname($form_path)?>/formoid-solid-green.css" type="text/css" />
<script type="text/javascript" src="<?=dirname($form_path)?>/jquery.min.js"></script>
<form class="formoid-solid-green" style="background-color:#FFFFFF;font-size:14px;font-family:'Roboto',Arial,Helvetica,sans-serif;color:#34495E;max-width:480px;min-width:150px" method="post"><div class="title"><h2>Registration form</h2></div>
	<div class="element-input<?frmd_add_class("input")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input" required="required" placeholder="username"/><span class="icon-place"></span></div></div>
	<div class="element-password<?frmd_add_class("password")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="password" name="password" value="" required="required" placeholder="Password"/><span class="icon-place"></span></div></div>
	<div class="element-password<?frmd_add_class("password1")?>"><label class="title"></label><div class="item-cont"><input class="large" type="password" name="password1" value="" placeholder="retype password"/><span class="icon-place"></span></div></div>
	<div class="element-name<?frmd_add_class("name")?>"><label class="title"><span class="required">*</span></label><span class="nameFirst"><input placeholder="Name First" type="text" size="8" name="name[first]" required="required"/><span class="icon-place"></span></span><span class="nameLast"><input placeholder="Name Last" type="text" size="14" name="name[last]" required="required"/><span class="icon-place"></span></span></div>
	<div class="element-input<?frmd_add_class("input5")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input5" required="required" placeholder="city"/><span class="icon-place"></span></div></div>
	<div class="element-email<?frmd_add_class("email")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="email" name="email" value="" required="required" placeholder="Email"/><span class="icon-place"></span></div></div>
	<div class="element-recaptcha<?frmd_add_class("captcha")?>"><label class="title"></label><script type="text/javascript">var RecaptchaOptions = {theme : "clean"};</script>
<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LfD7O0SAAAAAPjPJ5Mcrmu1egGBmZCl-T4qCkgQ&theme=clean"></script>
<noscript><iframe src="http://www.google.com/recaptcha/api/noscript?k=6LfD7O0SAAAAAPjPJ5Mcrmu1egGBmZCl-T4qCkgQ&hl=en" height="300" width="500" frameborder="0"></iframe></br>
<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea><input type="hidden" name="recaptcha_response_field" value="manual_challenge"></noscript>
<script type="text/javascript">if (/#invalidcaptcha$/.test(window.location)) (document.getElementById("recaptcha_widget_div")).className += " error"</script></div>
<div class="submit"><input type="submit" value="Register"/></div></form><script type="text/javascript" src="<?=dirname($form_path)?>/formoid-solid-green.js"></script>

<!-- Stop Formoid form-->
<?php endif; ?>

<?php frmd_end_form(); ?>