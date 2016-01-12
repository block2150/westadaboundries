<div class="well">
	<img class="img-polaroid" id="profile-image" src="/profile/<?php echo $_SESSION['user_username'] ?>/profile_image.png">    
	<div class="profile-image-fullname"><p><?php echo $_SESSION['user_fullname'] ?><br><span class="small"><?php echo $_SESSION['user_type'] ?></span></p></div>
</div>

                
<div class="side-well">
    <div class="side-well-title">
        <i class="icon-flag icon-white"></i>&nbsp;&nbsp;Quick Links
    </div>
    
	<div class="side-well-content">
        <div class="side-link"><a href="/<?php echo $_SESSION['user_username'] ?>" target="_blank"><i class="icon-camera-retro black"></i> View my public portfolio.</a></div>
        <div class="side-link"><a href="/portfolio/invites"><i class="icon-gift black"></i> Invite someone to join Fotoluv.com.</a></div>
        <div class="side-link"><a href="/portfolio/proofs_code"><i class="icon-upload black"></i> Request an upload code for proofs.</a></div>
	</div>
</div>


<?php includes("admin.options"); ?>


