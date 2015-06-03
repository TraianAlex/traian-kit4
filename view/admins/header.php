<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" title="CrearStep" href="#">Admin</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="javascript:history.go(-1);">Back</a></li>
          <li class="active"><a href="<?=SITE_ROOT?>/users/welcome">Home <span class="sr-only">(current)</span></a></li>
          <?php
  if(isset($_SESSION['user'])){?>
          <li><a href="<?=SITE_ROOT?>/users/log_out/logout">Log out</a></li>
          <li><?=URL::xlink('admins','profile', null, $_SESSION['user'].' Profile')?></li><?php
  }?>
          <li><?=URL::xlink('admins','users', null, 'Users')?></li>
          <li><?=URL::xlink('admins','register', null, 'Register')?></li>
          <li><?=URL::xlink('admins','visitors', null, 'Statistic')?></li>
          <li><?=URL::xlink('admins','test', null, 'Test')?></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!--end container -->
</nav>
<br><br><br>