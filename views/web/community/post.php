<?php

checkAccess();

?>

<html>
<head>
    <?php includes("page.page.meta"); ?>

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="/css/global.css" rel="stylesheet" media="screen">

    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/bootstrap-tab.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>

    <script>
		
		var user_id = "<?php echo $_SESSION['user_id'] ?>";
		var feed_id = "<?php echo $_GET['feed_id'] ?>";
			
        $(document).ready(function() {
			loadData();
            swapNav('home')

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
			
			$.post('/api/community.featured.portfolio',{
				},
				function(data) {
					var json = $.parseJSON(data);		
					if (json.user_id != "")
					{
						$("#featured-profile-image").html('<a href="/portfolio/' + json.username + '"><img class="img-polaroid" src="/portfolio/' + json.username + '/thumb_image.png" width="250"><div class="profile-image-fullname"><p>' + json.fullname + '<br><span class="small">' + json.type_name + '</span></p></div></a>')
						$("#featured-profile").show();
					}
				}
			);
        });
		
		function loadData()
		{	
			$("#logins").html("");
			$("#activity").html("");
		
			cnt = 0;
			$.post('/api/community.recent.logins',{
				},
				function(data) {
					var json = $.parseJSON(data);
					$.each(json, function (index) {
						if (json[index].file_name != "icon_image.png")
						{
							icon = "/portfolio/" + json[index].uuid + "/icon_" + json[index].file_name;
							url = "/portfolio/" + json[index].username;
							
							//load portfolio gallery
							$('<li/>')
								.append($('<a data-gallery="gallery"/>')
									.append($('<img>').prop('src', icon).prop('width', '50'))
									.prop('href', url)
									.addClass('icon thumbnail'))
								.appendTo(logins);
							cnt++;
							if (cnt == 12)
							{
								return false;
							}
						}
					});
		
					$("#portfolio-loading").hide();
					$("#portfolio-container").show();
		
					$("#manage-loading").hide();
					$("#manage-container").show();
					doLoadData = 0;
				}
			);
			homeFeed();
		}
		

		function homeFeed()
		{
			if (feed_id != "")
			{
				$.post('/api/feed.get',{
						feed_id: feed_id
					},
					function(data) {
						listFeed(data)
					}
				);
			}
			else
			{	
				$.post('/api/feed.list',{
					},
					function(data) {
						listFeed(data)
					}
				);
			}
		}
    </script>

</head>
<body>

<?php include_nav() ?>
<?php includes("model.div"); ?>

<div class="bg">
    <div id="page" class="container">
        <div class="row-fluid">
            <div class="span8 well">

				<div id="activity"></div>

            </div>
            <div class="span4">
                
                <div id="featured-profile" class="side-well hide">
                    <div class="side-well-title">
                        <i class="icon-heart icon-white"></i>&nbsp;&nbsp;Featured Portfolio
                    </div>
                    
                    <div id="featured-profile-image" class="featured"></div>
                </div>
                
                <div class="side-well">
                    <div class="side-well-title">
                        <i class="icon-user icon-white"></i>&nbsp;&nbsp;Recent Logins
                    </div>
                    <ul id="logins" class="side-list inline"></ul>
                </div>
                
                <div class="side-well">
                    <div class="side-well-title">
                        <i class="icon-flag icon-white"></i>&nbsp;&nbsp;Quick Links
                    </div>
                    
                    <div class="side-well-content">
                        <div class="side-link"><a href="/<?php echo $_SESSION['user_username'] ?>" target="_blank"><i class="icon-th black"></i> View my public portfolio.</a></div>
                        <div class="side-link"><a href="/portfolio/profile#edit"><i class="icon-pencil black"></i> Edit your profile</a></div>
                        <div class="side-link"><a href="/portfolio/invites"><i class="icon-gift black"></i> Invite someone to join Fotoluv.com.</a></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php includes("page.footer"); ?>
</body>
</html>