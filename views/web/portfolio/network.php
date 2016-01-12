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
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {


            swapNav('network')
            swapNav('network')

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
			loadData();
        });


		function loadData()
		{
			// Status Map
			// 1 = Pending
			// 3 = Connected
			// 5 = Ignored
			// 7 = blocked
			
			$("#cList").html("");
			$("#iList").html("");
			$("#vList").html("");
				
			$.post('/api/relationship.list',{
				},
				function(data) {
					var json = $.parseJSON(data);
					if (json.length == 0)
					{
						$("#cList").html('<div class="well text-center">Sorry, but you do not have any current connections.</div>');
					}
					else
					{
						$.each(json, function (index) 
						{	
							var user_id = json[index].user_id;
							var username = json[index].username;
							var fullname = json[index].fullname;
							var location = json[index].location;
							var type = getTypeName(json[index].type);
							var status = json[index].status;
							var created = jQuery.timeago(json[index].created);
							
							if (!location)
							{
								location = "";
							}
							
							if (json[index].status == "1")
							{	
								$('<div class="well"/>')
									.append('<div class="row-fluid">' +
												'<div class="span2"><img src="/portfolio/' + username + '/small_image.png" width="125" height="125" class="thumbnail"></div>' +
												'<div class="span6" style="padding-left: 10px;">' +
													'<h4 style="padding-left: 6px;">' + fullname + '&nbsp;&nbsp;<small>' + type + '</small></h4>' + 
													'<div class="small" style="margin:-10px 0 10px 0; padding-left: 6px;">' + location + '&nbsp;</div>' +
													'<ul class="inline">' +
														'<li><button id="acceptRelationship-'+user_id+'" class="btn btn-small btn-danger" type="button" data-loading-text="Please wait..." onclick="acceptRelationship(\'' + user_id + '\', 3)">Accept Invitation</button></li>' +
														'<li><button class="btn btn-small btn-inverse" type="button" onclick="window.location=\'/portfolio/' + username + '\';">View Portfolio</button></li>' +											
													'</ul>' +										
												'</div>' +
												'<div class="span4" style="font-size: 11px; text-align: right; padding-top: 12px;">' + created + '</div>' +
											'</div>')
									.appendTo(iList);
							}					
							if (json[index].status == "3")
							{	
								$('<div class="well"/>')
									.append('<div class="row-fluid">' +
												'<div class="span2"><img src="/portfolio/' + username + '/small_image.png" width="125" height="125" class="thumbnail"></div>' +
												'<div class="span6" style="padding-left: 10px;">' +
													'<h4 style="padding-left: 6px;">' + fullname + '&nbsp;&nbsp;<small>' + type + '</small></h4>' + 
													'<div class="small" style="margin:-10px 0 12px 0; padding-left: 6px;">' + location + '&nbsp;</div>' +
													'<ul class="inline">' +
														'<li><button class="btn btn-small btn-inverse" type="button" onclick="window.location=\'/portfolio/' + username + '\';">View Portfolio</button></li>' +											
														'<li><button class="btn btn-small btn-danger" type="button" onclick="quickSend(\'' + user_id + '\', \'' + fullname + '\', \'3\')">Send Message</button></li>' +											
													'</ul>' +										
												'</div>' +
												'<div class="span4" style="font-size: 11px; text-align: right; padding-top: 12px;">' + created + '</div>' +
											'</div>')
									.appendTo(cList);
							}
						});
					}
				}
			);
			
			
				
			$.post('/api/portfolio.view.list',{
				},
				function(data) {
					var json = $.parseJSON(data);
					$.each(json, function (index) {		
						
						var user_id = json[index].user_id;
						var username = json[index].username;
						var fullname = json[index].fullname;
						var location = json[index].location;
						var type = getTypeName(json[index].type);
						var relationship = json[index].relationship;
						var views = json[index].views;
						var new_views = json[index].new_views;
						var last_viewed = jQuery.timeago(json[index].last_viewed);
						
						if (!location)
						{
							location = "";
						}
						
						var showConnect = "display:none;";
						
						if (relationship == "0")
						{
							showConnect = "";
						}
						
						if (views == "1")
						{
							views = "viewed your profile <b>1</b> time";
						}
						else
						{
							views = "viewed your profile <b>"+views+"</b> times";
						}
					
						$('<div class="well"/>')
							.append('<div class="row-fluid">' +
										'<div class="span2"><img src="/portfolio/' + username + '/small_image.png" width="125" height="125" class="thumbnail"></div>' +
										'<div class="span6" style="padding-left: 10px;">' +
											'<h4 style="padding-left: 6px;">' + fullname + '&nbsp;&nbsp;<small>' + type + '</small></h4>' + 
											'<div class="small" style="margin:-10px 0 10px 0; padding-left: 6px;">' + location + '&nbsp;</div>' +
											'<ul class="inline">' +
												'<li id="invite-button-'+user_id+'" style="' + showConnect + '"><button class="btn btn-small btn-danger" type="button" onclick="createRelationship(\''+user_id+'\', \''+fullname+'\')">Connect</button></li>' +
												'<li><button class="btn btn-small btn-inverse" type="button" onclick="window.location=\'/portfolio/' + username + '\';">View Portfolio</button></li>' +											
											'</ul>' +										
										'</div>' +
										'<div class="span4" style="font-size: 11px; text-align: right; padding-top: 12px;">' + views + "<br>" + last_viewed + '</div>' +
									'</div>')
							.appendTo(vList);
						
						if ($("#vList").html() == "" && $("#iList").html() == "")
						{
							$("#cList").html('<div class="well text-center">Sorry, but you do not have any current connections.</div>');
						}
					});
				}
			);
		}


		function acceptRelationship(id, status)
		{
           $("#acceptRelationship-"+id).button('loading');
			$.post('/api/relationship.set',{
				user_id : id,
				status : status
			},
			function(data) {
				loadData();
			});
		}

    </script>

</head>
<body>

<?php include_nav() ?>
<?php includes("model.div"); ?>

<div class="bg">
    <div id="page" class="container">
        <div class="row-fluid">
            <div class="span4">
                <?php includes("side.portfolio"); ?>
            </div>
            <div class="span8 well">

                <div class="navbar">
                    <div class="navbar-inner">
                        <a class="brand" href="#">Your Network</a>
                        <ul class="nav" id="myTab">
                            <li class="active"><a href="#connections">Connections</a></li>
                            <li class="hide"><a href="#luvs">Luvs</a></li>
                            <li><a href="#views">Views</a></li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="connections">

                        <div class="hero-unit">

                            <h2>Fotoluv.com Connections</h2>

                            <p>Elevate your career by making the right connections with the right people.</p>

                        </div>

                        <div id="iList"></div>

                        <div id="cList"></div>

                    </div>
                    <div class="tab-pane" id="luvs">

                        <div class="hero-unit">

                            <h2>Coming Soon...</h2>

                            <p>Here you will be able to see who would like to connect with you and respond to their invitations.</p>

                        </div>

                        <div class="hero-content">
                            <?php includes("page.future.features"); ?>
                        </div>

                    </div>
                    <div class="tab-pane" id="views">

                        <div class="hero-unit">

                            <h2>Portfolio Views</h2>

                            <p>By knowing who is looking at your portfolio you will be better able to grow your network and establish yourself in the industry.</p>

                        </div>

                        <div id="vList"></div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php includes("page.footer"); ?>
</body>
</html>