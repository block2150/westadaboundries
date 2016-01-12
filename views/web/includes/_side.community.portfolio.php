<div class="well">
	<img class="img-polaroid" id="profile-image" src="/profile/<?php echo $_SESSION['profile_username'] ?>/profile_image.png">
	<div class="profile-image-fullname"><p><?php echo $_SESSION['profile_fullname'] ?><br><span class="small"><?php echo $_SESSION['profile_type'] ?></span></p></div>
</div>
                
<div class="side-well">
    <div class="side-well-title">
        <i class="icon-flag icon-white"></i>&nbsp;&nbsp;Quick Links
    </div>
    
	<div class="side-well-content">
        <div class="side-link"><a href="/<?php echo $_SESSION['profile_username'] ?>" target="_blank"><i class="icon-th black"></i> View <span class="profile-name"></span> public portfolio</a></div>
        
        <!--Relationship Links-->
        <div id="quickMessageLink" class="side-link link hide" onclick="quickSend('<?php echo $_SESSION['profile_id'] ?>', '<?php echo $_SESSION['profile_fullname'] ?>', '3');"><i class="icon-envelope black"></i> Send <?php echo $_SESSION['profile_fullname'] ?> a message</div>
        <div id="createRelationshipLink" class="side-link link" onclick="createRelationship();"><i class="icon-user black"></i> Connect with <?php echo $_SESSION['profile_fullname'] ?></div>
        <div id="pendingRelationship" class="side-link link hide"><i class="icon-user black"></i> You have sent an invite to <?php echo $_SESSION['profile_fullname'] ?></div>
        <div id="connectedRelationship" class="side-link link hide"><a href="/portfolio/network"><i class="icon-user black"></i> You are connected with <?php echo $_SESSION['profile_fullname'] ?></a></div>
        
	</div>
</div>

<?php includes("admin.options"); ?>