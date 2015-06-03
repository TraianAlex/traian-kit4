<!doctype html>
<html lang="en-US">
    <head>
        <base href="<?=BASE?>" target="_self">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><!-- X-UA-Compatible IE=11-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-signin-client_id" content="917238527872-6ue60ve4rcssho041366qv1ek1pkj8g5.apps.googleusercontent.com">
        <!--meta http-equiv="refresh" content="300">
        <meta http-equiv="Expires" content="-1"-->
        <title><?=ucfirst(basename($_SERVER['SCRIPT_FILENAME'],'.php')); ?> &#8212; Experimental site</title>

        <link type="text/css" rel="stylesheet" href="<?=SITE_ROOT?>/inc/css/bootstrap.css">
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" >
        <link type="text/css" rel="stylesheet" href="<?=SITE_ROOT?>/inc/css/main.css">
        <script src="<?=SITE_ROOT?>/inc/js/vendor/modernizr-2.8.3.min.js"></script>

        <!--script type="text/javascript" src="<?=SITE_ROOT?>/js/modernizr.custom.79759.js"></script-->
        <link type="text/css" rel="stylesheet" href="<?=SITE_ROOT?>/css/jquery.validate.password.css" />

        <script src="https://apis.google.com/js/platform.js" async defer></script>
        
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            // Load the SDK asynchronously
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));
              
              window.fbAsyncInit = function() {
                FB.init({
                  appId      : '732162843519058',
                  cookie     : true,  // enable cookies to allow the server to access 
                                      // the session
                  xfbml      : true,  // parse social plugins on this page
                  version    : 'v2.2' // use version 2.3
                });

                FB.getLoginStatus(function(response) {
                  statusChangeCallback(response);
                });
              };   
        </script>
    </head>
    <body>