<?php

if(file_exists('../config.php')) include_once '../config.php';

  if (Sessions::exist('error_message'))
     $error_message = preg_replace("/\\\\/", '', Sessions::get('error_message');
  else
     $error_message = "Something went wrong, and that's how you ended up here.";

 if (Sessions::exist('system_error_message'))
	$system_error_message = preg_replace("/\\\\/", '', Sessions::get('system_error_message');
 else
	$system_error_message = "No system-level error message was reported.";

  include "../view/head.php";
  include "../view/header.php";?>

  <section id="content">';
  ?><br>
  <div id="example">Sorry!</div>
  <div id="content">
    <h1>We're really sorry...</h1>
    <p><?=$error_message; ?>
      <span></p>
    <p>Don't worry, though, we've been notified that there's a
problem, and we take these things seriously. In fact, if you want to
contact us to find out more about what's happened, or you have any
concerns, just <a href="mailto:victor_traian@yahoo.com">email us</a>
and we'll be happy to get right back to you.</p>
    <p>In the meantime, if you want to go back to the page that caused
the problem, you can do that <a href="<?=SITE_ROOT?>">by
clicking here.</a> If the same problem occurs, though, you may
want to come back a bit later. We bet we'll have things figured
out by then. Thanks again... we'll see you soon. And again, we're
really sorry for the inconvenience.</p><?php
      debug_print("<hr />");
      debug_print("<p>The following system-level message was received: <b>{$system_error_message}</b></p>");?>
  </div><?php
   echo '</section>';
  include_once '../view/footer.php';
  if(Sessions::exist('error_message') || Sessions::exist('system_error_message')){
    Sessions::delete(['error_message' , 'system_error_message']);
    session_destroy();
  }