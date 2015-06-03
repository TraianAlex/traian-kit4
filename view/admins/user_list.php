<div class="container page-content">
    <div class="row">
        <h4>Users list</h4><hr><?php
    if($arrData['users']){
        foreach ($arrData['users'] as $data){
            extract($data);?>
                <div class="row">
                    <div class="col-md-1"><?=$username?></div>
                    <div class="col-md-2"><?=$email?></div>
                    <div class="col-md-1"><?=$uid?></div>
                    <div class="col-md-1"><?=$ip?></div>
                    <div class="col-md-2"><?=$created?></div>
                    <div class="col-md-2"><?=$updated?></div>
                </div><?php
        }
    }else{
        echo '<h4>There is no users registered in database</h4>';
    }
?>
    </div>
    <hr><h4>Admin list</h4><hr>
    <div class="row"><?php
    if($arrData['admins']){
        foreach ($arrData['admins'] as $data){
            extract($data);?>
                <div class="row">
                    <div class="col-md-1"><?=$username?></div>
                    <div class="col-md-2"><?=$email?></div>
                    <div class="col-md-1"><?=$uid?></div>
                    <div class="col-md-1"><?=$ip?></div>
                    <div class="col-md-2"><?=$created?></div>
                    <div class="col-md-2"><?=$updated?></div>
                </div><?php
        }
    }else{
        echo '<h4>There is no admins registered in database</h4>';
    }
 ?></div>
</div>