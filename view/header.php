<?php

file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : print "no file page rules";?>
<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <nav class="navbar navbar-inverse navbar-fixed-top">
              <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" title="CrearStep" href="#">My framework</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="<?=SITE_ROOT?>/users/nologin_area">Home <span class="sr-only">(current)</span></a></li>
                    <?php
            if(isset($_SESSION['user'])){?>
                    <li><a href="<?=SITE_ROOT?>/users/log_out" onclick="logout_all()">Log out</a></li>
                    <li><a href="<?=SITE_ROOT?>/users/profile"><?=$_SESSION['user']?> Profile</a></li><?php
            }
            if(!isset($_SESSION['user'])){?>
                    <li><a class="sign_in" href="<?=SITE_ROOT?>/users">Sign in</a></li><?php
            }
            if (isset($_SESSION ['id']) && $_SESSION ['id'] == sha1(K1 . sha1(session_id() . K1))) {
                $h = new Crypt_HMAC(KEY);?>
                 <li><a href="<?=SITE_ROOT?>/<?=$h->create_parameters(['class' => 'admins',
                                                      'page' => 'users',
                                                        'id' => null]);?>">Edit</a></li><?php
            }?>
                    <li><a href="<?=SITE_ROOT?>/users/login_area">Protected</a></li>

                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">kit <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Action</a></li>
                          <li><a href="#">Another action</a></li>
                          <li><a href="#">Something else here</a></li>
                          <li class="divider"></li>
                          <li><a href="#">Separated link</a></li>
                          <li class="divider"></li>
                          <li><a href="#">One more separated link</a></li>
                        </ul>
                      </li>
                      
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!--end container -->
        </nav>
<br><br><br>
<script type="text/javascript">

function GoogleLogout()
  {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
          $('#google_login_box').show();
          $('#google_profile_box').hide();
    });
  }
  
  function FBLogout()
  {
    FB.logout(function(response) {
        $('#fblogin').show();
        $('#fbstatus').hide();
    });
  }

function logout_all(){
    FBLogout();
    GoogleLogout();
}
</script>