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
    <link href="/css/global.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" media="screen" href="/css/bootstrapSwitch.css">


    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>
    <script src="/js/jquery.date-format.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>
    
    <script type="text/javascript" src="/js/load-image.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-image-gallery.js"></script>

    <script>
		var DoSettings = 0;
		var user_id = "<?php echo $_SESSION['user_id'] ?>";
			
        $(document).ready(function() 
		{
			var url = document.location.toString();
			if (url.match('#')) {
				$('.nav a[href=#'+url.split('#')[1]+']').tab('show') ;
			} 

            swapNav('profile');
            loadData();
			listUserFeed();

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

			$('#profile-sex-male').click(function (e) {
				swap("male");
			});

			$('#profile-sex-female').click(function (e) {
				swap("female");
			});

			$('#profile-job-add').click(function (e) {
				$("#profile-job-add").toggle();
				$("#profile-job-form").toggle();
				$("#list").toggle();
			});

			$('#profile-job-button-cancel').click(function (e) {
				$("#profile-job-add").toggle();
				$("#profile-job-form").toggle();
				$("#list").toggle();
			});
			
			// save profile form
            $("#profile-save").on("click", function()
            {
                $("#profile-success").hide();
                $("#profile-alert").hide();
                $("#profile-save").button('loading');
                var isValid = true;

                if (isValid)
                {
                    $.post('/api/profile.info.set', $("form#profile-form").serialize(),
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.user_id != "null") {
                                $("#profile-success").show();
                                $("#profile-save").button('reset');
                                loadData();
								$("body").scrollTop(0);
                            } else {
                                $("#profile-message").html(json.message);
                                $("#profile-alert").show();
                                $("#profile-save").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#profile-save").button('reset');
                }
            });

            $('#public-profile').on('switch-change', function () {
                setSettings("public-profile")
            });
			
			
			//add profile job experience
            $("#profile-job-button-save").on("click", function()
            {
                var isValid = true;				
                if (isValid)
                {
                    $.post('/api/profile.job.set',{
                        id : $("#profile-job-id").val(),
                        name : $("#profile-job-name").val(),
                        location: $("#profile-job-location").val(),
                        month: $("#profile-job-month").val(),
                        day: $("#profile-job-day").val(),
                        year: $("#profile-job-year").val(),
                        details: $("#profile-job-details").val()
                    	},
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.user_id != "null") {
								$("#profile-job-name").val("");
								$("#profile-job-location").val("");
								$("#profile-job-month").val("");
								$("#profile-job-day").val("");
								$("#profile-job-year").val("");
								$("#profile-job-details").val("");
								
								$("#profile-job-add").toggle();
								$("#profile-job-form").toggle();
                                loadData();
                            } else {
                            }

                        }
                    );
                }
                else
                {
                }
            });
			
			
			
			DoSettings = 1;
        });


        //
        // Div Swapping
        //
		
		function swap(v)
		{
			if (v == "male")
			{
				$("#profile-size-row").hide();	
				$("#view-profile-size").hide();					
			}
			if (v == "female")
			{
				$("#profile-size-row").show();	
				$("#view-profile-size").show();	
			}
		}
		

        function setSettings(setting)
        {
			if (DoSettings == 1)
			{
				status = 0;
				if ($("#" + setting +"-checked").is(":checked"))
				{
					status = 1;
				}
				$.post('/api/profile.settings.set',{
						setting : setting,
						status: status
					},
					function(data) {
					}
				);
			}
        }

		function deleteJob(id)
		{
			$.post('/api/profile.job.delete',{
				id : id
				},
				function(data) {
					loadData();
				}
			);
		}
		
		function editJob(id)
		{
			var strDate = $("#job-date-" + id).html();
			var temp = strDate.split(" ");
			var dateParts = temp[0].split("-");
			
			var year = dateParts[0];
			var month = dateParts[1];
			var day = dateParts[2];			
			
			$("#profile-job-id").val(id);
			$("#profile-job-name").val($("#job-name-" + id).html());
			$("#profile-job-location").val($("#job-location-" + id).html());
			$("#profile-job-month").val(month);
			$("#profile-job-day").val(day);
			$("#profile-job-year").val(year);
			$("#profile-job-details").val($("#job-details-" + id).html());
		
			$("#profile-job-add").toggle();
			$("#profile-job-form").toggle();
			$("#list").toggle();
		}

        function loadData()
        {
            $("#gallery").html("");
            $("#list").html("");
            $("#jobs").html("");			
            $("#thumbs").html("");
			
            $.post('/api/profile.info.get',{
                },
                function(data) {
                    var json = $.parseJSON(data);
		
                    if (json.user_id == "" || json.user_id == null)
                    {
						
                    }
                    else
                    {
						if(json.public_profile == "1")
						{
							$('#public-profile').bootstrapSwitch('setState', true);
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
							$("#skills").html("Edit your profile to add a list of your own skills");
						}
						else
						{
							$("#skills").html('<ul class="inline">' + skills + '</ul>');
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
										'</div>' +											
										'<div class="row-fluid">' +
											'<div class="span7"></div>' +
											'<div class="span5 text-right">' +
												'<button class="btn btn-mini btn-danger" type="button" onclick="editJob(' + job_id + ')">Edit</button> &nbsp;' +
												'<button class="btn btn-mini btn-inverse" type="button" onclick="deleteJob(' + job_id + ')">Delete</button>' +
											'</div>' +
										'</div>')
								.appendTo(list);
							
                        });
						
						if (job_id != "")
						{
							$("#list").show();
							$("#job-title").show();
						}

                        $("#view-personal-features").show();
                        $("#view-profile-content").show();
                        $("#gallery").show();
                        $("#thumbs").show();
                    }

                }
            );

			$.post('/api/portfolio.images.list',{
					category_id : 1
				},
				function(data) {
					var json = $.parseJSON(data);
					if (json.status == "failed") {
					}
					else
					{
						var json = $.parseJSON(data);
						$.each(json, function (index) {
							icon = "/portfolio/" + json[index].user_id + "/icon_" + json[index].file_name;
							small = "/portfolio/" + json[index].user_id + "/small_" + json[index].file_name;
							thumb = "/portfolio/" + json[index].user_id + "/thumb_" + json[index].file_name;
							profile = "/portfolio/" + json[index].user_id + "/profile_" + json[index].file_name;
							url = "/portfolio/" + json[index].user_id + "/" + json[index].file_name;

							checked = '';
							if (json[index].user_image == "1") {
								$('#profile-image').attr("src", profile);
								checked = 'checked="checked"';
							}
							//load portfolio gallery
							$('<li/>')
								.append($('<a data-gallery="gallery"/>')
									.append($('<img>').prop('src', small).prop('width', '125'))
									.prop('href', url)
									.addClass('thumbnail'))
								.appendTo(thumbs);
								
							preload([url]);
						});

						$("#portfolio-loading").hide();
						$("#portfolio-container").show();

						$("#manage-loading").hide();
						$("#manage-container").show();
						doLoadData = 0;
					}
				}
			);
        }

    </script>

</head>
<body>

    <?php include_nav() ?>

    <!-- modal-gallery is the modal dialog used for the image gallery -->
    <div id="modal-gallery" class="modal modal-gallery hide fade modal-fullscreen in" tabindex="-1">
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>

    <div class="bg">
        <div id="page" class="container">
            <div class="row-fluid">
                <div class="span4">
                    <?php includes("side.portfolio"); ?>
                </div>
                <div class="span8 well">

                    <div class="navbar">
                        <div class="navbar-inner">
                            <a class="brand" href="#">Your Profile</a>
                            <ul class="nav" id="myTab">
                            	<li><a href="#feed">Activity</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Profile <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#view" data-toggle="tab">View Profile</a></li>
                                        <li><a href="#edit" data-toggle="tab">Edit Profile</a></li>
                                    </ul>
                                </li>
                                <li><a href="#settings">Settings</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="feed">
                        
                        	<div class="hero-unit share">
                            	<div class="clearfix">
                                    <textarea id="share-update" placeholder="Share an update..."></textarea>
                                    <button id="share-update-btn" class="btn btn-inverse pull-right" type="button" onClick="shareUpdate();">Share</button>
								</div>
                            </div>
                        
                        	<div id="activity">
                            	
                            </div>
                        
                        </div>
                        <div class="tab-pane" id="view">


                            <div class="hero-unit">

                                <h2><span id="view-profile-name">Your Profile</span> <small id="view-type_name"></small></h2>
                                <div id="view-profile-location"></div>

                                <p id="view-profile-summary">Take a moment to setup your Fotoluv.com profile.</p>

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
                            
                            <ul id="thumbs" data-toggle="modal-gallery" data-target="#modal-gallery" class="inline hide"></ul>
                            
                            <div id="view-profile-content" class="hero-content hide">
                            
                                <div class="section-header">
                                    <h3>Specialties</h3>
                                </div>    
                                
                                <div id="skills" class="well"></div>
                            		
                                    <div id="job-title" class="section-header hide">
                                        <h3>Experience</h3>
                                    </div>    
                                    
                                    <div id="jobs"></div>
                                
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
                                                
                        <div class="tab-pane" id="edit">

                            <div class="hero-unit">

                                <h2>Your Modeling Profile</h2>

                                <p>Think of your profile as your own modeling resume.  It will be used by others to find out more about you, to see what experience you have as a model and to see if they would like to connect with you on Fotoluv.com.</p>

                            </div>

                            <div class="hero-content">

                                <form id="profile-form" class="form-horizontal" autocomplete="off">
                                    <div class="control-group">

                                        <div class="page-header">
                                            <h3>Basic Infomation</h3>
                                        </div>

                                        <div id="profile-alert" class="alert alert-error hide">
                                            <button type="button" class="close" onClick="hideAlert('profile-alert');">&times;</button>
                                            <h4>Oops!</h4>
                                            <div id="profile-message"></div>
                                        </div>

                                        <div id="profile-success" class="alert alert-success hide">
                                            <button type="button" class="close" onClick="hideAlert('profile-success');">&times;</button>
                                            <h4>Thank You</h4>
                                            <div>Your modeling profile has been updated.  Please make sure you keep your profile updated as much as possible.</div>
                                        </div>

                                    </div>
									
                                    <div class="well">
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Name:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-name" name="profile-name" placeholder="Enter Your Name" required value="">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Location:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-location" name="profile-location" placeholder="Where are you located" required value="">
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Profile Type:</label>
                                            <div class="controls">
                                                <select id="profile-type" name="profile-type">
                                                    <?php includes("select.profile.type"); ?>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Birthday:</label>
                                            <div class="controls">
    
                                                    <select class="inline" name="profile-month" id="profile-month">
                                                        <?php includes("date.months"); ?>
                                                    </select>
    
                                                    <select class="inline" name="profile-day" id="profile-day">
                                                        <?php includes("date.days"); ?>
                                                    </select>
    
                                                    <select class="inline" name="profile-year" id="profile-year">
                                                        <?php includes("date.years"); ?>
                                                    </select>
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Summary:</label>
                                            <div class="controls">
                                                <textarea id="profile-summary" name="profile-summary" rows="3" required></textarea>
                                                <small class="help-block"><span class="label label-important">Important</span> Your summary should be a short overview of your experience and background. Make sure you keep it short.  You will have opportunity to go into more detail, but if you put to much in this section it will be trimmed down by our system.</small>
                                            </div>
                                        </div>
                                        
                                    </div>
                                        
                                    <div class="control-group">

                                        <div class="page-header">
                                            <h3>Personal Features</h3>
                                        </div>

                                    </div>

									
                                    <div class="well">
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Sex:</label>
                                            <div class="controls">
    
    
                                                <label class="radio inline">
                                                    <input type="radio" name="profile-sex" id="profile-sex-male" value="male">
                                                    Male
                                                </label>
                                                <label class="radio inline">
                                                    <input type="radio" name="profile-sex" id="profile-sex-female" value="female">
                                                    Female
                                                </label>
    
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Hair:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-hair" name="profile-hair" placeholder="What color is your hair?" required value="">
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Eyes:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-eyes" name="profile-eyes" placeholder="What color are your eyes?" required value="">
                                            </div>
                                        </div>
    
                                        <div class="control-group" id="profile-size-row">
                                            <label class="control-label" for="inputEmail">Size:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-size" name="profile-size" placeholder="What what is your dress size?" required value="">
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Height:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-height" name="profile-height" placeholder="How tall are you?" required value="">
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Weight:</label>
                                            <div class="controls">
                                                <input type="text" id="profile-weight" name="profile-weight" placeholder="How much do you weight?" required value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="page-header">
                                            <h3>Specialties</h3>
                                        </div>
									    <span class="label label-important">Important</span> Please select the areas below that you have experience in, don't just select areas of interest.  The better you represent yourself the better chance you have at that next opportunity.
                                    </div>

                                    <div class="well">
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-high-fashion" name="profile-skills-high-fashion" value="High Fashion">
                                                    High Fashion
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-glamour" name="profile-skills-glamour" value="Glamour">
                                                    Glamour
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-runway" name="profile-skills-runway" value="Runway">
                                                    Runway
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-fashion-editorial" name="profile-skills-fashion-editorial" value="Fashion Editorial">
                                                    Fashion Editorial
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-commercial-print" name="profile-skills-commercial-print" value="Commercial Print">
                                                    Commercial Print
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-showroom" name="profile-skills-showroom" value="Showroom">
                                                    Showroom
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-fashion-fitness" name="profile-skills-fashion-fitness" value="Fitness">
                                                    Fitness
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-swimwear" name="profile-skills-swimwear" value="Swimwear">
                                                    Swimwear
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-lingerie" name="profile-skills-lingerie" value="Lingerie">
                                                    Lingerie
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-plus-size" name="profile-skills-plus-size" value="Plus Size">
                                                    Plus Size
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-petite" name="profile-skills-petite" value="Petite">
                                                    Petite
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-mature" name="profile-skills-mature" value="Mature">
                                                    Mature
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-teen" name="profile-skills-teen" value="Teen">
                                                    Teen
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-child" name="profile-skills-child" value="Child">
                                                    Child
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-baby" name="profile-skills-baby" value="baby">
                                                    Baby
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-hands" name="profile-skills-hands" value="Parts: Hands">
                                                    Parts: Hands
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-hair" name="profile-skills-hair" value="Parts: Hair">
                                                    Parts: Hair
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-eyes" name="profile-skills-eyes" value="Parts: Eyes">
                                                    Parts: Eyes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-lips" name="profile-skills-lips" value="Parts: Lips">
                                                    Parts: Lips
                                                </label>
                                            </div>
                                            <div class="span4">
                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-teeth" name="profile-skills-teeth" value="Parts: Teeth">
                                                    Parts: Teeth
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-back" name="profile-skills-back" value="Parts: Back">
                                                    Parts: Back
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-legs" name="profile-skills-legs" value="Parts: Legs">
                                                    Parts: Legs
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-feet" name="profile-skills-feet" value="Parts: feet">
                                                    Parts: Feet
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-other" name="profile-skills-other" value="Parts: Other">
                                                    Parts: Other
                                                </label>

                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-promotional" name="profile-skills-promotional" value="Promotional">
                                                    Promotional
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-trade-show" name="profile-skills-trade-show" value="Trade Show">
                                                    Trade Show
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-character" name="profile-skills-character" value="Character">
                                                    Character
                                                </label>

                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-spokesmodel" name="profile-skills-spokesmodel" value="Spokesmodel">
                                                    Spokesmodel
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-presenter" name="profile-skills-presenter" value="Presenter">
                                                    Presenter
                                                </label>
                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-narrator" name="profile-skills-narrator" value="Narrator">
                                                    Narrator
                                                </label>

                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-movies" name="profile-skills-acting-movies" value="Acting: Movies">
                                                    Acting: Movies
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-tv" name="profile-skills-acting-tv" value="Acting: TV">
                                                    Acting: TV
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-commercials" name="profile-skills-acting-commercials" value="Acting: Commercials">
                                                    Acting: Commercials
                                                </label>

                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-theatre" name="profile-skills-acting-theatre" value="Acting: Theatre">
                                                    Acting: Theatre
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-shorts" name="profile-skills-acting-shorts" value="Acting: Digital Short">
                                                    Acting: Digital Short
                                                </label>

                                            </div>
                                            <div class="span4">

                                                <label class="checkbox">
                                                    <input type="checkbox" id="profile-skills-acting-promotional" name="profile-skills-acting-promotional" value="Acting: Promotional">
                                                    Acting: Promotional
                                                </label>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">

                                        <div class="page-header">
                                            <h3>Experience</h3>
                                        </div>

                                    </div>

                                    <div class="control-group">
                                        
                                        <div id="profile-job-add" class="well link text-center">
                                        	<i class="icon-plus"></i> ADD A JOB, SHOW or EVENT
                                        </div>
										
                                        <div id="profile-job-form" class="hide">
                                        
                                            <div class="control-group">
                                                <label class="control-label" for="profile-job-name">Name:</label>
                                                <div class="controls">
                                                    <input type="text" id="profile-job-name" name="profile-job-name" placeholder="" required value="">
                                                    <input type="hidden" id="profile-job-id" name="profile-job-id" value="">
                                                    <small class="help-block">Enter the name of the company, show or event that you worked at.</small>
                                                </div>
                                            </div>
                                        
                                            <div class="control-group">
                                                <label class="control-label" for="profile-job-location">Location:</label>
                                                <div class="controls">
                                                    <input type="text" id="profile-job-location" name="profile-job-location" placeholder="" required value="">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="inputEmail">Date:</label>
                                                <div class="controls">
        
                                                        <select class="inline" name="profile-job-month" id="profile-job-month">
                                                            <?php includes("date.months"); ?>
                                                        </select>
        
                                                        <select class="inline" name="profile-job-day" id="profile-job-day">
                                                            <?php includes("date.days"); ?>
                                                        </select>
        
                                                        <select class="inline" name="profile-job-year" id="profile-job-year">
                                                            <?php includes("date.years"); ?>
                                                        </select>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="profile-job-details">Details:</label>
                                                <div class="controls">
                                                    <textarea id="profile-job-details" name="profile-job-details" rows="3" required></textarea>
                                                    <small class="help-block"><span class="label label-important">Important</span> You should put everything you think is needed to help people understand what you did and who you worked with at this event.  The more information the better.</small>
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                            	<button id="profile-job-button-save" class="btn btn-small btn-danger" type="button">Save</button>
												<button id="profile-job-button-cancel" class="btn btn-small btn-inverse" type="button">Cancel</button>
                                            </div>
                                        </div>
                                    </div>

									<div id="list" class="hide"></div>									
                                    
                                    <div class="control-group">
                                        <div class="page-header">
                                            <h3>Additional Information</h3>
                                        </div>
                                    </div>
									
                                    <div class="well">
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="profile-education">Education:</label>
                                            <div class="controls">
                                                <textarea id="profile-education" name="profile-education" rows="3" required></textarea>
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="profile-achievments">Achievments:</label>
                                            <div class="controls">
                                                <textarea id="profile-achievments" name="profile-achievments" rows="3" required></textarea>
                                            </div>
                                        </div>
    
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Interests:</label>
                                            <div class="controls">
                                                <textarea id="profile-interests" name="profile-interests" rows="3" required></textarea>
                                            </div>
                                        </div>
                                	</div>
    
                                    <div class="text-center">
	                                    <button type="button" id="profile-save" class="btn btn-large btn-danger" data-loading-text="Saving your changes...">Save Your Changes</button>
                                    </div>
                                </form>


                            </div>



                        </div>
                        
                        <div class="tab-pane" id="settings">                 
                   
                            <div id="settings-header" class="hero-unit">
                                <h2>Profile Settings</h2>
                                <p>Customizing your profile is easy with our simple settings page.  Remember to check back often for new features are they are added to Fotoluv.com</p>
                            </div>

                            <div class="hero-content">

                                <span class="row-fluid">
                                    <span class="span10">
                                        <h3>Show on Public Portfolio</h3>
                                        This setting will make you profile information available on your public portfolio.  This means that when people try to visit your portfolio, from outside the fotoluv.com network, they will not be able to see it.
                                    </span>
                                    <span class="span2 switch-container">
                                        <div id="public-profile" class="switch">
                                            <input type="checkbox" id="public-profile-checked" />
                                        </div>
                                    </span>
                                </span>
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