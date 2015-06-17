<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?=SITE_ROOT?>/inc/sign_in_forgot2_files/formoid1/formoid-solid-light-green.css" type="text/css" />

<div class="container page-content">

    <!--form name="Login" action="<?php //SITE_ROOT?>/users/login/<?php //T?>" class="formoid-solid-light-green"  method="post">
          <input type="text" name="user" required="required" placeholder="Username" autocorrect="off" onFocus="this.value=\"\"" pattern="^[A-Za-z0-9\@_.\'-]+$"/>
          <input type="password" name="pwd" required="required" placeholder="Password" onFocus="this.value=\"\"" autocorrect="off"/><!--span class="icon-place"></span--><!--/div--><!--/div-->
        <!--input type="submit" style="width:6em;" name="submit" value="Sign In" />
            <?php //CSRF::tokenize()?>
    </form>
    </div>
    <div class="row"-->
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-5 alert-info"><?=Errors::show_errors()?></div>
            <div class="col-md-3"></div>
        </div>
        
        <div class="row">
            <a href="<?=SITE_ROOT?>/users/forgot_password">Forgot password ?</a>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form name="Login" method="post" action="<?=SITE_ROOT?>/users/login/<?=T?>" class="form-horizontal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputEmail3"  name="user" placeholder="Username">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputPassword3" name="pwd" placeholder="Password">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                      <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="submit" class="btn btn-primary" value="Sign In">
                      <?=CSRF::tokenize()?>
                        or <a class="btn btn-default" href="<?=SITE_ROOT?>/users/register">Register</a>
                    </div>
                  </div>
                </form>
                </div>
            <div class="col-md-3"></div>
          </div>
          <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3">
                    <div>&nbsp;</div>
                    <div class="g-signin2" data-longtitle="true" data-onsuccess="Google_signIn" data-theme="light" data-width="200"></div>
                </div>
                <div class="col-md-3">
                    <div id="status"></div>
                    <div id="fblogin">
                        <img src="<?=SITE_ROOT?>/images/fb.png" style="cursor:pointer" onclick="FBLogin();">
                    </div>
               </div>
               <div class="col-md-2"></div>
          </div>
</div>

<script type="text/javascript">

function Google_signIn(googleData) 
{
    var profile = googleData.getBasicProfile(); //get google profile details
    $('#google_profile_box').show();
    $('#google_login_box').hide();

    $('#Gname').text("Name : "+profile.getName());
    $('#Gemail').text("Email : "+profile.getEmail());
    $('#Gimg').html("<img src='"+profile.getImageUrl()+"'>");
    $('#Gid').text("Google ID : "+profile.getId());

    //update to database
    update_user_data(profile);
}

function update_user_data(response) 
{
      $.ajax({
            type: "POST",
            dataType: 'json',
            data: response,
            url: 'http://localhost/traian-kit4/new-pdo/users/oath_ajax_login',
            success: function(msg) {
               if(msg.error== 1)
               {
                alert('Something Went Wrong!');
               }
            }
      });
}

/*--------------------------------------------------------------------------------------------------*/

// This is called with the results from from FB.getLoginStatus().
              function statusChangeCallback(response) {

                if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    // we need to hide FB login button
                    $('#fblogin').hide();
                    //fetch data from facebook
                    getUserInfo();
                } else if (response.status === 'not_authorized') {
                    // The person is logged into Facebook, but not your app.
                    $('#status').html('Please log into this app.');
                } else {
                    // The person is not logged into Facebook, so we're not sure if
                    // they are logged into this app or not.
                    $('#status').html('Please log into facebook');
                }
              }
              
                // This function is called when someone finishes with the Login
                // Button.  See the onlogin handler attached to it in the sample
                // code below.
                function checkLoginState() {
                  FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);
                  });
                }
                
function FBLogin()
{
     FB.login(function(response) {
         if (response.authResponse) {
             getUserInfo(); //Get User Information.
         } else{
            alert('Authorization failed.');
        }
     },{scope: 'public_profile,email'});
}

function getUserInfo() {
    FB.api('/me', function(response) {

      $.ajax({
            type: "POST",
            dataType: 'json',
            data: response,
            url: 'http://localhost/traian-kit4/new-pdo/users/oath_fb_login',
            success: function(msg) {
                if(msg.error== 1){
                   alert('Something Went Wrong!');
                } else {
                   $('#fbstatus').show();
                   $('#fblogin').hide();
                   $('#fbname').text("Name : "+msg.name);
                   $('#fbemail').text("Email : "+msg.email);
                   $('#fbfname').text("First Name : "+msg.first_name);
                   $('#fblname').text("Last Name : "+msg.last_name);
                   $('#fbid').text("Facebook ID : "+msg.id);
                   $('#fbimg').html("<img src='http://graph.facebook.com/"+msg.id+"/picture'>");
                }
            }
      });

    });
}
</script>