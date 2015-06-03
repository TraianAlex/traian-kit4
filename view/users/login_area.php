<!--script type="text/javascript">
var requiredfrom = "index.php";
if (document.referrer.indexOf(requiredfrom) == -1) {
alert("You must come to this page from " + requiredfrom);
window.location=requiredfrom;
}
</script-->

<div class="container page-content">
    <div class="row"><script src="<?=SITE_ROOT?>/js/breadcrumb.js"></script></div><br>
    <div class="row">
    <h4>Login area</h4><?php
    if (Sessions::exist('submit')) {
        echo "<h4>" . Sessions::get('submit') . " " . Sanitize::htmlout(Sessions::get('user')) . " in login area!</h4>";
    }?>
    <div id="tlkio" data-channel="hey" style="width:100%;height:400px;"></div>
    <script async src="http://tlk.io/embed.js" type="text/javascript"></script>
    </div>
</div>
<div class="container page-content">
	<div class="row">
            <iframe frameborder="0" scrolling="no" style="border:0px" src="https://books.google.ca/books?id=niMBBQAAQBAJ&lpg=PA171&ots=CcU35T1Djx&dq=meta%20content%3D%20authenticity_token%20name%3D%20csrf-param%20%2F&pg=PA171&output=embed" width=500 height=500></iframe>
	</div>
</div>
