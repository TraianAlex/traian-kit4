<div class="container page-content">
    <div class="row">   
        <?=URL::create_crumbs()?>
    </div>
    <div class="panel panel-default">     
        <div class="panel-heading">
            <div class="panel-title">
                <h3>Forgot your password?</h3>
            </div>
        </div>
        <div class="panel-body">
            <div class="alert-info"><?=Errors::show_errors()?></div>
            <form action="<?=SITE_ROOT?>/users/send_pass/<?=T?>" method="post">
                <input type="hidden" name="from" value="PHP site">
                <div class="form-group">
                    <input class="form-control" type="email" name="f_email" id="email" value="" onFocus="this.value=\'\'"
                    required placeholder="Please enter your email address as you will be sent a message" onblur="verificaemail(this)"
                    pattern="^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+.([a-zA-Z])+([a-zA-Z])+)">
                </div><?=CSRF::tokenize()?>
     
                <div class="form-group">
                    <input type="submit" name="send_id" value="Send link" class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="panel-footer">
            <a class="btn btn-default" href="<?=SITE_ROOT?>">Sign in</a>
            <a class="btn btn-default" href="<?=SITE_ROOT?>/users/register">Register</a>
        </div>     
    </div>
</div>