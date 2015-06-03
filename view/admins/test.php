<div class="container page-content">
    <div class="row"><?php
    
        $adr = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
    	echo $adr, '<br>'; 
    	echo 'SERVER_DOCUMENT_ROOT: ' .$_SERVER['DOCUMENT_ROOT']. '<br>';
    	echo 'SITE_ROOT:' .SITE_ROOT. '<br>';
    	echo 'SERVER_SCRIPT_FILENAME: ' .$_SERVER['SCRIPT_FILENAME'], '<br>';
        echo URL::xdelete('admins', 'test', '');?>
        
    </div>
</div>