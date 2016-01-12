<?php

checkAccess();

?>

<html>
<head>
    <?php includes("page.meta"); ?>

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <link rel="stylesheet" media="screen" href="/css/bootstrap-image-gallery.css">
    <link rel="stylesheet" media="screen" href="/css/global.css">
    <link rel="stylesheet" media="screen" href="/css/dropzone.css">
    <link rel="stylesheet" media="screen" href="/css/bootstrapSwitch.css">

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-button.js"></script>
    <script type="text/javascript" src="/js/bootstrap-tab.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/jquery.date-format.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script type="text/javascript" src="/js/global.js"></script>

    <script type="text/javascript" src="/js/load-image.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-image-gallery.js"></script>



    <script>

        var doLoadData = 1;
		var profileType = "";
		var fullname = "";
		var username = "";
		var relationship = "";
		var user_fullname = "<?php echo $_SESSION['user_fullname'] ?>";
		var photo = "<?php echo $_GET['photo'] ?>";

        $(document).ready(function() {

            loadData();
            swapNav('portfolio')

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
			
			$.post('/api/portfolio.view', {
			},
				function(data) {
				}
			);

        });
		function swap(v)
		{
			if (v == "male")
			{
				$("#view-profile-size").hide();					
			}
			if (v == "female")
			{
				$("#view-profile-size").show();	
			}
		}
		


        function loadData() {

            if (doLoadData == 1)
            {
                $("#gallery").html("");
            	$("#jobs").html("");

                $("#portfolio-container").hide();
                $("#portfolio-welcome").hide();
                $("#portfolio-loading").show();
				
				$.post('/api/profile.info.get',{
					},
					function(data) {
						var json = $.parseJSON(data);
						user_id = json.user_id;
						profileType = json.type_name;
						fullname =  json.fullname;
						relationship = json.relationship;
						username = json.username;
						
						$("#share-update").attr("placeholder", "Share something with " + fullname + "...");
						
						listUserFeed(user_id);
						
						if (json.user_id == "" || json.user_id == null)
						{
	
						}
						else
						{										
							if (json.settings[0]["featured"] == "0")
							{
								$('#featurePortfolioLink').html('<i class="icon-heart black"></i> Feature this portfolio');
							}
							else
							{
								$('#featurePortfolioLink').html('<i class="icon-remove black"></i> Unfeature this portfolio');
							}
	
							// Status Map
							// 1 = Pending
							// 3 = Connected
							// 5 = Ignored
							// 7 = blocked
							
							if (relationship == "1")
							{
								$('#createRelationshipLink').hide();
								$('#pendingRelationship').show();	
							}
							
							if (relationship == "3")
							{
								$('#createRelationshipLink').hide();
								$('#connectedRelationship').show();	
								$('#quickMessageLink').show();	
							}
							
							if (relationship == "5")
							{
								$('#createRelationshipLink').hide();
							}
							
							if (relationship == "7")
							{
								$('#createRelationshipLink').hide();
							}
									
							$.each(json, function (index) {
								$("#view-" + index).html(json[index]);							
							});		
							
							var skills = "";	
							$.each(json.kv, function (index) {
	
								// Set View Data
								if ($("#view-" + json.kv[index]["k"]))
								{
									$("#view-" + json.kv[index]["k"]).html(json.kv[index]["v"]);
										
									if ($("#view-" + json.kv[index]["k"] + "-block") && json.kv[index]["v"] != "")
									{
										$("#view-" + json.kv[index]["k"] + "-block").show();	
									}
								}
	
								if ($("#" + json.kv[index]["k"]))
								{
									if ($("#" + json.kv[index]["k"]).prop("type") == "checkbox")
									{
										$("#" + json.kv[index]["k"]).prop("checked", true)
									}
									$("#" + json.kv[index]["k"]).val(json.kv[index]["v"]);
								}
								
								if (json.kv[index]["k"].indexOf("profile-skills") !== -1)
								{
									skills += '<li><i class="icon-heart"></i>&nbsp;&nbsp;' + json.kv[index]["v"] + '</li>';
								}
								
								// Handle Radio Buttons
								if (json.kv[index]["k"] == "profile-sex")
								{
									if (json.kv[index]["v"] == "male")
									{
										$("#profile-sex-male").prop("checked", true);
										swap("male");
									}
									if (json.kv[index]["v"] == "female")
									{
										$("#profile-sex-female").prop("checked", true)
										swap("female");
									}
								}
								
							});								
							
							if (skills == "")
							{
								$("#skills-header").hide();
								$("#skills").hide();
							}
							else
							{
								$("#skills").html('<ul class="inline">' + skills + '</ul>');
								$("#skills-header").show();
								$("#skills").show();
							}
							
							job_id = "";
							$.each(json.jobs, function (index) {
								job_id = json.jobs[index]["id"];
								job_name = json.jobs[index]["name"];
								job_location = json.jobs[index]["location"];
								job_date = $.format.date(json.jobs[index]["job_date"], "M/d/yyyy");
								job_details = json.jobs[index]["details"];
								
								//load manage list
								$('<div/>')
									.prop('id', "job-" + job_id)
									.addClass('well')
									.append(
											'<div class="row-fluid">' +
												'<div id="job-name-'+job_id+'" class="span8 bold">' + job_name + '</div>' +
												'<div id="job-date-'+job_id+'" class="span4 text-right">' + job_date + '</div>' +
											'</div>' +	
											'<div class="row-fluid">' +
												'<div id="job-location-'+job_id+'" class="span12">' + job_location + '</div>' +
											'</div>' +		
											'<div class="row-fluid">' +
												'<div id="job-details-'+job_id+'" class="span12">' + job_details + '</div>' +
											'</div>')
									.appendTo(jobs);
								
							});
							
							if (job_id != "")
							{
								$("#jobs-header").show();
								$("#jobs").show();
							}
							if (profileType == "model")
							{
								$("#view-personal-features").show();
							}
							$("#view-profile-content").show();
							$("#gallery").show();
                            $("#thumbs").show();
						}
	
					}
				);


				var ShowPortfolio = 0;
                $.post('/api/portfolio.images.list',{
                    },
                    function(data) {
                        var json = $.parseJSON(data);
                        if (json.status == "failed") {
                            $("#portfolio-loading").hide();
                            $("#portfolio-welcome").show();
                        }
                        else
                        {
                            var json = $.parseJSON(data);
                            $.each(json, function (index) {
								if (json[index].file_name != "")
								{
									icon = "/portfolio/" + json[index].user_id + "/icon_" + json[index].file_name;
									small = "/portfolio/" + json[index].user_id + "/small_" + json[index].file_name;
									thumb = "/portfolio/" + json[index].user_id + "/thumb_" + json[index].file_name;
									profile = "/portfolio/" + json[index].user_id + "/profile_" + json[index].file_name;
									url = "/portfolio/" + json[index].user_id + "/" + json[index].file_name;
	
									file_name_id = json[index].file_name.replace(".", "-").replace("_", "-");
	
									if (json[index].user_image == "1") {
										$('#profile-image').attr("src", profile);
									}
									
									
									var icon_heart = "icon-heart-empty";
									if (json[index].luved != "0") {
										icon_heart = "icon-heart";
									}
									
									//load portfolio gallery
									$('<div id="portfolio-image-'+json[index].id+'" class="fotoluv-container" data-id="'+json[index].id+'" />')
										.addClass('portfolio-image')
										.append('<div id="fotoluv-gallery-'+json[index].id+'" class="fotoluv link" onclick="fotoluv(\'gallery\', \''+json[index].id+'\', \''+json[index].user_id+'\', \''+json[index].file_name+'\');"><icon id="fotoluv-gallery-icon-'+json[index].id+'" class="' + icon_heart + '"></icon></>')
										.append($('<a id="'+ file_name_id +'" data-gallery="gallery"/>')
											.append($('<img>').prop('src', url).prop('width', '250'))
											.prop('href', url)
											.addClass('thumbnail'))											
										.appendTo(gallery);
									
									//load fullscreen gallery
									$('<div id="portfolio-image-'+json[index].id+'" class="fotoluv-container-fullscreen" data-id="'+json[index].id+'" />')
										.addClass('portfolio-image')
										.append('<div id="fotoluv-fullscreen-'+json[index].id+'" class="fotoluv link" onclick="fotoluv(\'fullscreen\', \''+json[index].id+'\', \''+json[index].user_id+'\', \''+json[index].file_name+'\');"><icon id="fotoluv-fullscreen-icon-'+json[index].id+'" class="' + icon_heart + '"></icon></>')
										.append($('<a data-gallery="gallery"/>')
											.append($('<img>').prop('src', url).prop('width', '450'))
											.prop('href', url)
											.addClass('thumbnail-fullscreen'))											
										.appendTo(fullscreen);
									
									//load profile gallery
									$('<li/>')
										.append($('<a data-gallery="gallery"/>')
											.append($('<img>').prop('src', small).prop('width', '125'))
											.prop('href', url)
											.addClass('thumbnail'))
										.appendTo(thumbs);
									preload([url]);
									ShowPortfolio = 1;
								}
                            });
							
							if (ShowPortfolio == 1)
							{
								$("#portfolio-loading").hide();
								$("#portfolio-container").show();
								doLoadData = 0;
							}
							else
							{
								$("#portfolio-loading").hide();
								$("#portfolio-welcome").show();	
							}
                        }
			
						if (photo !=- "")
						{
							photo = photo.replace(".", "-").replace("_", "-");	
							$("#" + photo).click();
						}
                    }
                );
            }
        }

		var isFullscreen = 0;
		function toggleFullScreen()
		{
			if (isFullscreen == 1)
			{
				$(".container").css("width", "940px");
				$(".slogan").css("padding", "15px 0");
				$(".slogan").css("cursor", "");
				$(".slogan").css("color", "");
		  	 	$("#nav-sub").show();
				$("#logo-tag").html('<img src="/images/share-the-love.png" />');
				
				isFullscreen = 0;
			}
			else
			{
				$(".container").css("width", "1200px");
				$(".slogan").css("padding", "10px 0 0 0 ");
				$(".slogan").css("cursor", "pointer");
				$(".slogan").css("color", "white");
				
		  	 	$("#nav-sub").hide();
				
				$("#logo-tag").html('<i class="icon-resize-full icon-large" onclick="toggleFullScreen()";></i>');
				isFullscreen = 1;
			}
			
			$("#portfolio-layout").toggle();
			$("#portfolio-fullscreen").toggle();
		}

    </script>

</head>
<body>

    <?php include_nav() ?>
    <?php includes("model.div"); ?>

    <!-- modal-gallery is the modal dialog used for the image gallery -->
    <div id="modal-gallery" class="modal modal-gallery hide fade modal-fullscreen in" tabindex="-1">
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>
    
    <div id="portfolio-fullscreen" class="hide">
        <div id="fullscreen" data-toggle="modal-gallery" data-target="#modal-gallery" class="columns-fullscreen"></div>
    </div>

    <div id="portfolio-layout" class="bg">
        <div id="page" class="container">
            <div class="row-fluid">
                <div class="span4">
                    <?php includes("side.community.portfolio"); ?>
                </div>
                <div class="span8 well">

                    <div class="navbar">
                        <div class="navbar-inner">
                            <a class="brand" href="#">Portfolio</a>
                            <ul class="nav" id="myTab">
                                <li class="active" onClick="loadData();"><a href="#portfolio">Photos</a></li>
                                <li><a href="#feed">Activity</a></li>
                                <li><a href="#profile">Profile</a></li>
                            </ul>
                            <div class="pull-right" style="color: #777777; cursor: pointer; padding-top: 10px;" onClick="toggleFullScreen();">
	                            <i class="icon-resize-full icon-large"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="portfolio">

                            <div id="portfolio-loading" class="loading text-center"><img src="/images/loading.gif" /></div>

                            <div id="portfolio-welcome" class="hero-unit hide">
                                <h2>No Portfolio Photos available</h2>
                                <p>We are sorry but there are no photos available for this portfolio.   Please check back later for updates.</p>
                            </div>

                            <div id="portfolio-container" class="hero-unit hide">
                                <div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" class="columns"></div>
                            </div>

                        </div>
                        <div class="tab-pane" id="feed">
                        
                        	<div class="hero-unit share">
                            	<div class="clearfix">
                                    <textarea id="share-update" placeholder=""></textarea>
                                    <button id="share-update-btn" class="btn btn-inverse pull-right" type="button" onClick="shareUpdate(user_id);">Share</button>
								</div>
                            </div>
                        
                        	<div id="activity">
                            	
                            </div>
                        
                        </div>
                        <div class="tab-pane" id="profile">


                            <div class="hero-unit">

                                <h2><span id="view-profile-name">Profile Information</span> <small id="view-type_name"></small></h2>

                                <p id="view-profile-summary">This profile hasn't been setup yet.  Please check back later to see if it has been updated.</p>

								<br />

                                <ul id="view-personal-features" class="hide inline medium">
									<li>Age: <span id="view-age" class="features-info"></span></li>
                                    <li>Sex: <span id="view-profile-sex" class="features-info"></span></li>                    
                                    <li>Hair: <span id="view-profile-hair" class="features-info"></span></li> 
                                    <li>Eyes: <span id="view-profile-eyes" class="features-info"></span></li>
                                    <li id="view-profile-size">Size: <span id="view-profile-size" class="features-info"></span></li>
                                    <li>Height: <span id="view-profile-height" class="features-info"></span></li>
                                    <li>Weight: <span id="view-profile-weight" class="features-info"></span></li>
                                </ul>
                            </div>
                            
                            <ul id="thumbs" data-toggle="modal-gallery" data-target="#modal-gallery" class="hide thumbnails"></ul>
                            
                            <div id="view-profile-content" class="hero-content hide">
                            
                                <div id="skills-header" class="section-header hide">
                                    <h3>Skills and Expertise</h3>
                                </div>    
                                
                                <div id="skills" class="well hide"></div>
                            
                                <div id="jobs-header" class="section-header hide">
                                    <h3>Experience</h3>
                                </div>    
                                
                                <div id="jobs" class="hide"></div>
                            
                                
                                <div id="view-profile-education-block" class="hide">
                                    <div class="section-header">
                                        <h3>Education</h3>
                                    </div>    
									<div id="view-profile-education" class="well"></div>
                                </div>
                                
                                <div id="view-profile-achievments-block" class="hide">
                                    <div class="section-header">
                                        <h3>Achievments</h3>
                                    </div>    
									<div id="view-profile-achievments" class="well"></div>
                                </div>
                                
                                <div id="view-profile-interests-block" class="hide">
                                    <div class="section-header">
                                        <h3>Interests</h3>
                                    </div>    
									<div id="view-profile-interests" class="well"></div>
                                </div>
                                
							</div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php includes("page.footer"); ?>
</body>
</html>