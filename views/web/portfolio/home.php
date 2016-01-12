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
    <script src="/js/jquery.timeago.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-button.js"></script>
    <script type="text/javascript" src="/js/bootstrap-tab.js"></script>
    <script type="text/javascript" src="/js/dropzone.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script type="text/javascript" src="/js/global.js"></script>

    <script type="text/javascript" src="/js/load-image.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-image-gallery.js"></script>



    <script>

        var doLoadData = 1;
        var doSettings = 0;
		var photo = "<?php echo $_GET['photo'] ?>";

        $(document).ready(function() {

            loadData();
            loadSettings();
            swapNav('portfolio')


			$('#fotoluvSearch').change(function() {
				userSearch();
			});

            $('#public-portfolio').on('switch-change', function () {
                setSettings("public-portfolio")
            });

            $('#show-descriptions').on('switch-change', function () {
                setSettings("show-descriptions")
            });
			
        });

        function setSettings(setting)
        {
            if (doSettings == 1)
            {
                status = 0;
                if ($("#" + setting +"-checked").is(":checked"))
                {
                    status = 1;
                }
                $.post('/api/portfolio.settings.set',{
                        setting : setting,
                        status: status
                    },
                    function(data) {
                    }
                );
            }
        }

        function clearDropzone() {
            doLoadData = 1;
            var myDropzone = Dropzone.forElement("#protfolio-dropzone");
            myDropzone.removeAllFiles();
        }

        function loadData() {

            if (doLoadData == 1)
            {
                $("#gallery").html("");
                $("#list").html("");
                $("#plist").html("");

                $("#portfolio-container").hide();
                $("#portfolio-welcome").hide();
                $("#portfolio-loading").show();

                $("#manage-container").hide();
                $("#manage-welcome").hide();
                $("#manage-loading").show();
				
				var ShowPortfolio = 0;
                $.post('/api/portfolio.images.list',{
                    },
                    function(data) {
                        var json = $.parseJSON(data);
                        if (json.status == "failed") {
                            $("#portfolio-loading").hide();
                            $("#portfolio-welcome").show();

                            $("#manage-loading").hide();
                            $("#manage-welcome").show();
                        }
                        else
                        {
                            var json = $.parseJSON(data);
                            $.each(json, function (index) {
								if (json[index].file_name != "")
								{
									icon = "/portfolio/" + json[index].user_id + "/icon_" + json[index].file_name;
									thumb = "/portfolio/" + json[index].user_id + "/thumb_" + json[index].file_name;
									profile = "/portfolio/" + json[index].user_id + "/profile_" + json[index].file_name;
									url = "/portfolio/" + json[index].user_id + "/" + json[index].file_name;
	
									file_name_id = json[index].file_name.replace(".", "-").replace("_", "-");
	
									checked = '';
									if (json[index].user_image == "1") {
										$('#profile-image').attr("src", profile);
										checked = 'checked="checked"';
									}
									
									if (json[index].category_id == 2)
									{
										//load manage list
										$('<div/>')
											.prop('id', "manage-" + json[index].id)
											.addClass('row-fluid')
											.addClass('list-row')
											.append('<div class="span4"><a data-gallery="gallery"><img src="' + thumb +'" id="image-' + json[index].id + '" class="thumbnail" width="160" /></a></div>' +
													'<div class="span8 list-form"><textarea id="image-desc-' + json[index].id + '">' + json[index].description + '</textarea>' +
														'<div class="row-fluid">' +
															'<div class="span7"><label class="checkbox"><input type="checkbox" id="profile-image-checkbox-' + json[index].id + '" value="true"  onclick="setProfileImage(this, ' + json[index].id  + ', \'' + profile + '\');" ' + checked + '><small>Make this your profile image</small></label></div>' +
															'<div class="span5 text-right">' +
																'<button class="btn btn-mini btn-danger" type="button" onclick="addProof(' + json[index].id + ')">Add to Portfolio</button> &nbsp;' +
																'<button class="btn btn-mini btn-inverse" type="button" onclick="confirmDel(' + json[index].id + ', \''+thumb+'\')">Delete</button>' +
															'</div>' +
													'</div></div>')
											.appendTo(plist);
									}
									else
									{
										//load portfolio gallery
										$('<div id="portfolio-image-'+json[index].id+'" class="fotoluv-container" data-id="'+json[index].id+'" />')
											.addClass('portfolio-image')
											.append($('<a id="'+ file_name_id +'" data-gallery="gallery"/>')
												.append($('<img>').prop('src', url).prop('width', '250'))
												.prop('href', url)
												.addClass('thumbnail'))											
											//.append('<div id="fotoluv-'+json[index].id+'" class="fotoluv link" onclick="fotoluv('+json[index].id+');"><icon id="fotoluv-icon-'+json[index].id+'" class="icon-heart-empty"></icon></>')
											.appendTo(gallery);
		
										//load manage list
										$('<div/>')
											.prop('id', "manage-" + json[index].id)
											.addClass('row-fluid')
											.addClass('list-row')
											.append('<div class="span4"><img src="' + thumb +'" id="image-' + json[index].id + '" class="thumbnail" width="160" /></div>' +
													'<div class="span8 list-form"><textarea id="image-desc-' + json[index].id + '">' + json[index].description + '</textarea>' +
														'<div class="row-fluid">' +
															'<div class="span7"><label class="checkbox"><input type="checkbox" id="profile-image-checkbox-' + json[index].id + '" value="true"  onclick="setProfileImage(this, ' + json[index].id  + ', \'' + profile + '\');" ' + checked + '><small>Make this your profile image</small></label></div>' +
															'<div class="span5 text-right">' +
																'<button class="btn btn-mini btn-danger" type="button" onclick="save(' + json[index].id + ')">Save</button> &nbsp;' +
																'<button class="btn btn-mini btn-inverse" type="button" onclick="confirmDel(' + json[index].id + ', \''+thumb+'\')">Delete</button>' +
															'</div>' +
													'</div></div>')
											.appendTo(list);
									
											//load portfolio fullscreen
											$('<div id="portfolio-image-'+json[index].id+'" class="fotoluv-container-fullscreen" data-id="'+json[index].id+'" />')
												.addClass('portfolio-image')
												.append($('<a data-gallery="gallery"/>')
													.append($('<img>').prop('src', url).prop('width', '450'))
													.prop('href', url)
													.addClass('thumbnail-fullscreen'))											
												//.append('<div id="fotoluv-'+json[index].id+'" class="fotoluv link" onclick="fotoluv('+json[index].id+');"><icon id="fotoluv-icon-'+json[index].id+'" class="icon-heart-empty"></icon></>')
												.appendTo(fullscreen);
									}
	
									preload([url]);
									ShowPortfolio = 1;
								}
                            });
							
							if (ShowPortfolio == 1)
							{
								$("#portfolio-loading").hide();
								$("#portfolio-container").show();
	
								$("#manage-loading").hide();
								$("#manage-container").show();
								doLoadData = 0;
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

        function loadSettings() {
            $.post('/api/portfolio.settings.list',{
                },
                function(data) {
                    var json = $.parseJSON(data);

                    if(json.public_portfolio == "1")
                    {
                        $('#public-portfolio').bootstrapSwitch('setState', true);
                    }

                    if(json.show_descriptions == "1")
                    {
                        $('#show-descriptions').bootstrapSwitch('setState', true);
                    }

                    if(json.featured == "0")
                    {
                        $('#featurePortfolioLink').html('<i class="icon-heart black"></i> Feature this portfolio');
                    }
					else
					{
                        $('#featurePortfolioLink').html('<i class="icon-remove black"></i> Unfeature this portfolio');
					}
					
					

                    $("#settings-loading").hide();
                    $("#settings-form").show();
                    doSettings = 1;
                }
            );
        }

        function setProfileImage(elm, id, src) {

            $('#profile-image').attr("src", src);

            $('input:checkbox').not(elm).prop('checked', false);

            $.post('/api/profile.image.set',{
                    id: id
                },
                function(data) {
                    doLoadData = 1;
                }
            );
        }

        function save(id) {

            $.post('/api/portfolio.image.description.set',{
                    id: id,
                    description: $("#image-desc-" + id).val()
                },
                function(data) {
                    doLoadData = 1;
                }
            );
        }
		
		function addProof(id) {

            $.post('/api/proof.add.portfolio',{
                    id: id,
                    description: $("#image-desc-" + id).val()
                },
                function(data) {
                    doLoadData = 1;
                    loadData();
                }
            );
        }

        function confirmDel(id, src)
        {
            $("#delete-photo-id").val(id);
            $('#delete-photo-src').attr("src", src);

            $('#modal-delete').modal({
                keyboard: false,
                backdrop: true,
                show: true
            })
        }

        function del()
        {
            id = $("#delete-photo-id").val();
            clearProfileImage = 0;

            if ($("#profile-image-checkbox-" + id).is(":checked")) {
                $('#profile-image').attr("src", "/images/no_image.png");
                clearProfileImage = 1;
            }
            $.post('/api/portfolio.images.delete',{
                    id: id,
                    clearProfileImage: clearProfileImage
                },
                function(data) {
                    doLoadData = 1;

                    $("#delete-photo-id").val("");
                    $('#delete-photo-src').attr("src", "");
                    $('#modal-delete').modal("hide");

                    loadData();
                }
            );

        }
		
		function sendFeedNotification()
		{
			setFeed("portfolio.updated", "", "");
		}
        //
        // Div Swapping
        //

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
		   		$(".navbar-search").show();
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
		   		$(".navbar-search").hide();
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

    <!-- modal-gallery is the modal dialog used for the image gallery -->
    <div id="modal-gallery" class="modal modal-gallery hide fade modal-fullscreen in" tabindex="-1">
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>
    
    <div id="portfolio-fullscreen" class="hide">
        <div id="fullscreen" data-toggle="modal-gallery" data-target="#modal-gallery" class="columns-fullscreen"></div>
    </div>

    <div id="modal-delete" class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Confirm delete?</h3>
        </div>
        <div class="modal-body">
            <input type="hidden" value="" id="delete-photo-id" />
            <div class="row-fluid">
                <div class="span4"><img src="/images/no_photo.png" id="delete-photo-src" width="160" class="thumbnail" /> </div>
                <div class="span8">
                    <h4>Are you sure you want to delete this photo from your portfolio?</h4>

                    <br />

                    <small><b>NOTE:</b> if this photo is currently selected as your profile photo you will have to select a new one from the remaining photos.</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-inverse" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-danger" onClick="del();">Delete Photo</a>
        </div>
    </div>

    <div id="portfolio-layout" class="bg">
        <div id="page" class="container">
            <div class="row-fluid">
                <div class="span4">
                    <?php includes("side.portfolio"); ?>
                </div>
                <div class="span8 well">

                    <div class="navbar">
                        <div class="navbar-inner">
                            <a class="brand" href="#">Your Portfolio</a>
                            <ul class="nav" id="myTab">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Photos <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#portfolio" data-toggle="tab">Portfolio</a></li>
                                        <li><a href="#proofs" data-toggle="tab">Proofs</a></li>
                                    </ul>
                                </li>
                                <li><a href="#upload" data-toggle="tab">Upload</a></li>
                                <li><a href="#manage" data-toggle="tab" onClick="loadData();">Manage</a></li>
                                <li><a href="#settings" data-toggle="tab">Settings</a></li>
                            </ul>
                            <div class="pull-right nav-fullscreen" onClick="toggleFullScreen();">
	                            <i class="icon-resize-full icon-large"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="portfolio">

                            <div id="portfolio-welcome" class="hero-unit hide">
                                <h2>Welcome to Your Fotoluv Portfolio</h2>
                                <p>Thank you for creating an new account wiht Fotoluv.com.  Here is your new portfolio.  We suggest you take a look around and get familiar with the site.</p>

                                <p>The first thing you are going to want to do is upload your own photos.  To do this click on the link above titled "upload".</p>
                            </div>

                            <div id="portfolio-container" class="hero-unit hide">
                                <div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" class="columns"></div>
                            </div>

                        </div>
                        <div id="proofs" class="tab-pane hide">

                            <div class="hero-unit">
                                <h2>Fotoluv.com Proofs</h2>
                                <p>Proofs provide and easy way for you to get images from any photography.  All you have to do is send them an upload code and they will do the rest.  Once they have uploaded your proofs you will be able to come back here to view them.</p>
                            </div>
                            

                            <div id="plist"></div>

                        </div>
                        <div class="tab-pane" id="upload">

                            <form action="/module/portfolio.images.upload.php" class="dropzone" id="protfolio-dropzone"></form>


                            <p>
                                <button class="btn btn-large btn-block btn-danger" type="button" onClick="clearDropzone();">REMOVE UPLOADED FILES</button>
                                <div class="text-center" style="margin-top: 10px;">NOTE: This will not remove them from your portfolio.</div>
                            </p>

                        </div>
                        <div class="tab-pane" id="manage">

                            <div id="manage-loading" class="loading text-center"><img src="/images/loading.gif" /></div>

                            <div id="manage-welcome" class="hero-unit hide">
                                <h2>Mange Your Portfolio</h2>
                                <p>This area will allow you to edit, delete or update your portfolio images with description information.  You will also be able to select what image you would like to use for your profile.</p>
                            </div>

                            <div id="manage-container" class="hero-unit hide">
                                <div id="list"></div>
                            </div>

                        </div>
                        <div class="tab-pane" id="settings">

                            <div id="settings-loading" class="loading text-center"><img src="/images/loading.gif" /></div>

                            <div id="settings-form" class="hide">

                                <div id="settings-header" class="hero-unit">
                                    <h2>Portfolio Settings</h2>
                                    <p>Customizing your portfolio is easy with our simple settings page.  Remember to check back often for new features are they are added to Fotoluv.com</p>
                                </div>

                                <div class="hero-content">

                                    <span class="row-fluid">
                                        <span class="span10">
                                            <h3>Public Protfolio</h3>
                                            This setting will turn on or off your public portfolio.  This means that when people try to visit your portfolio, from outside the fotoluv.com network, they will not be able to see it.
                                        </span>
                                        <span class="span2 switch-container">
                                            <div id="public-portfolio" class="switch">
                                                <input type="checkbox" id="public-portfolio-checked" />
                                            </div>
                                        </span>
                                    </span>

                                    <span class="row-fluid">
                                        <hr />
                                    </span>

                                    <span class="row-fluid">
                                        <span class="span10">
                                            <h3>Show Descriptions</h3>
                                            Turning this feature on wll display the description when viewing your photo, both for your public and private portfolio.
                                        </span>
                                        <span class="span2 switch-container">
                                            <div id="show-descriptions" class="switch">
                                                <input type="checkbox" id="show-descriptions-checked" />
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
    </div>
    <?php includes("page.footer"); ?>
</body>
</html>