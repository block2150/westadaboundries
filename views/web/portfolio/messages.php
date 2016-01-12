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

    <link rel="stylesheet" href="/css/token-input.css" type="text/css" />
    <link rel="stylesheet" href="/css/token-input-facebook.css" type="text/css" />

    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>
    <script src="/js/jquery.date-format.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/jquery.tokeninput.js"></script>

    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>
    
    <script type="text/javascript" src="/js/load-image.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-image-gallery.js"></script>

    <script>
		var DoSettings = 0;
		var user_id = "<?php echo $_SESSION['user_id'] ?>";
		var message_id = "";
		var delete_message_id = "";
		var reply_to = "";
			
        $(document).ready(function() 
		{
			SentMessages();
			InboxMessages();
			
			var url = document.location.toString();
			if (url.match('#')) {
				$('.nav a[href=#'+url.split('#')[1]+']').tab('show') ;
			} 

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            swapNav('messages');
			
			
            $("#message-to").tokenInput("/api/message.list.users", {
                preventDuplicates: true,
                theme: "facebook"
            });
			
        });
		
		function SendMessage()
		{
            $.post('/api/message.send',{
                    message: $("#message-text").val(),
					to: $("#message-to").tokenInput("get"),
					parent_id: ""
                },
                function() {
					SentMessages();
					$('.nav a[href=#sent]').tab('show');
					$("#message-to").tokenInput("clear");
				 	$("#message-text").val("");
                }
            );
		}
		
		function ReplyMessage()
		{
            $.post('/api/message.send',{
                    message: $("#message-reply").val(),
					to: "[" + reply_to + "]",
					parent_id: message_id
                },
                function() {
					ViewMessage(message_id)
				 	$("#message-reply").val("");
                }
            );
		}
		
		function SentMessages()
		{
			$("#MessagesSent").empty();
            $.post('/api/message.list.sent',{
                },
                function(data) {
					
					json = $.parseJSON(data);
					
					$.each(json, function (index) {
						
						message_id = json[index].message_id;
						parent_id = json[index].parent_id;
						message = json[index].message.substring(0, 300);
						message_date = jQuery.timeago(json[index].message_date);						
						recipients = $.parseJSON(json[index].recipients);
						viewed = json[index].viewed;
						
						var reply_text = "";
						if (parent_id != "")
						{
							message_id	= parent_id;
							reply_text = "reply"
						}
						
						var new_text = "";
						if (viewed == "0")
						{
							new_text = '<i class="icon-inbox"></i> ';
						}
						
						rText = "";
						$.each(recipients, function (id) {
							rText += '<a href="/portfolio/' + recipients[id].username + '">' + recipients[id].fullname + '</a>, ';
						});
						
						if (json[index].message.length > 300)
						{
							message += "...";
						}
						
						rText = rText.substring(0, rText.length - 2);
						
						$('<div/>')
							.prop('id', "message-" + message_id)
							.append('<div class="well">' +
										'<div class="message-to pull-left">' + new_text + '' + rText + ' <small>' + reply_text + '</small></div>' + 
										'<div class="message-date text-right">' + message_date + '</div>' + 
										'<div class="message-text" onclick="ViewMessage(\'' + message_id + '\');">' + message + '</div>' + 
										'<div class="message-actions text-right"><span class="link" onclick="ViewMessage(\'' + message_id + '\');">View</span> &middot; <span class="link" onclick="ViewMessage(\'' + message_id + '\');">Reply</span> &middot; <span class="link" onclick="DeleteMessage(\'' + message_id + '\');">Delete</span></div>' + 
									'</div>'								
							)
							.appendTo(MessagesSent);
					});
				});
		}
		
		function InboxMessages()
		{
			$("#MessagesInbox").empty();
            $.post('/api/message.list.inbox',{
                },
                function(data) {
					
					json = $.parseJSON(data);
					
					$.each(json, function (index) {
						
						message_id = json[index].message_id;
						parent_id = json[index].parent_id;
						message = json[index].message.substring(0, 300);
						message_date = jQuery.timeago(json[index].message_date);
						recipients = $.parseJSON(json[index].recipients);
						viewed = json[index].viewed;
						
						var reply_text = "";
						if (parent_id != "")
						{
							message_id	= parent_id;
							reply_text = "reply"
						}						
						
						var new_text = "";
						if (viewed == "0")
						{
							new_text = '<i class="icon-inbox"></i> ';
						}
						
						if (json[index].message.length > 300)
						{
							message += "...";
						}
						
						users = "";
						from = "";
						from_image = "";
						
						$.each(recipients, function (id) {
							users += '<a href="/portfolio/' + recipients[id].username + '">' + recipients[id].fullname + '</a>, ';
							if (recipients[id].sender == "1")
							{
								from = '<a href="/portfolio/' + recipients[id].username + '">' + recipients[id].fullname + '</a>';
								from_image = '<a class="icon thumbnail" href="/portfolio/' + recipients[id].username + '"><img src="/portfolio/' + recipients[id].username + '/icon_image.png"></a>';
							}
							
							
						});
						
						
						users = users.substring(0, users.length - 2);
						
						$('<div/>')
							.prop('id', "message-" + message_id)
							.append('<div class="well">' +
										'<div class="message-image" style="float: left;">' + from_image + '</div>' +
										'<div class="message-view" style="float: right; width: 465px;">' + 
											'<div class="message-from" style="float: left;">' + new_text + '' + from + ' <small>' + reply_text + '</small></div>' + 
											'<div class="message-date text-right" style="float: right;">' + message_date + '</div>' + 
											'<br style="clear: both;">' +
											'<div class="message-text" onclick="ViewMessage(\'' + message_id + '\');">' + message + '</div>' + 
											'<div class="message-actions text-right"><span class="link" onclick="ViewMessage(\'' + message_id + '\');">View</span> &middot; <span class="link" onclick="ViewMessage(\'' + message_id + '\');">Reply</span> &middot; <span class="link" onclick="DeleteMessage(\'' + message_id + '\');">Delete</span></div>' + 
										'</div>' + 
										'<br style="clear: both;">' +
									'</div>' + 			
									'<br style="clear: both;">' 					
							)
							.appendTo(MessagesInbox);
					});
				});
		}
		
		function ViewMessage(id)
		{
			$("#ViewMessageComplete").empty();
            $.post('/api/message.get',{
					message_id : id
                },
                function(data) {
					
					json = $.parseJSON(data);
					
					$.each(json, function (index) {
						
						message_id = json[index].message_id;
						message = json[index].message.replace(/\n/g, '<br />');
						message_date = jQuery.timeago(json[index].message_date);
						recipients = $.parseJSON(json[index].recipients);
						
						users = "";
						from = "";
						from_image = "";
						
						$.each(recipients, function (id) {
							users += '<a href="/portfolio/' + recipients[id].username + '">' + recipients[id].fullname + '</a>, ';
							if (recipients[id].sender == "1")
							{
								from = '<a href="/portfolio/' + recipients[id].username + '">' + recipients[id].fullname + '</a>';
								from_image = '<a class="icon thumbnail" href="/portfolio/' + recipients[id].username + '"><img src="/portfolio/' + recipients[id].username + '/icon_image.png"></a>';
							}
							if (user_id != recipients[id].user_id)
							{
								reply_to += '{"id":"' + recipients[id].user_id + '", "name":"' + recipients[id].fullname + '"},'
							}
						});
						
						
						users = users.substring(0, users.length - 2);
						reply_to = reply_to.substring(0, reply_to.length - 1);
						
						$('<div/>')
							.prop('id', "message-" + message_id)
							.append('<div class="well">' +
										'<div class="message-image" style="float: left;">' + from_image + '</div>' +
										'<div class="message-view" style="float: right; width: 465px;">' + 
											'<div class="message-from" style="float: left;">' + from + '</div>' + 
											'<div class="message-date text-right" style="float: right;">' + message_date + '</div>' + 
											'<br style="clear: both;">' +
											'<div class="message-text">' + message + '</div>' + 
											'<div class="message-actions text-right"><span class="link" onclick="DeleteMessage(\'' + message_id + '\');">Delete</span></div>' + 
										'</div>' + 
										'<br style="clear: both;">' +
									'</div>' 			
									
									
							)
							.appendTo(ViewMessageComplete);
					});
					$('<div/>')
						.append('<textarea id="message-reply" required style="height:125px; width: 100%"></textarea>' +
                                '<button id="share-update-btn" class="btn btn-inverse pull-right" type="button" onClick="ReplyMessage();">Send Reply</button>'								
						)
						.appendTo(ViewMessageComplete);
					$("#message-users").html(users);
					$('.nav a[href=#view]').tab('show');
				});
			
		}
		
		
		
		function DeleteMessage(id)
		{
			delete_message_id = id;
			$('#confirmDelete').modal('toggle')
		}
		
		function ConfirmDeleteMessage()
		{	
			$('#confirmDelete').modal('toggle')
			$.post('/api/message.delete',{
				message_id : delete_message_id
			},
			function() {
				InboxMessages();
				SentMessages();
				ViewMessage(message_id);
				if (delete_message_id == message_id)
				{
					$('.nav a[href=#inbox]').tab('show');
				}
			});
		}
    </script>

</head>
<body>

    <!-- Modal -->
    <div id="confirmDelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Confirm Delete</h3>
      </div>
      <div class="modal-body">
        <p>Are you sure you would like to delete this email?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-danger" onClick="ConfirmDeleteMessage();">Delete</button>
      </div>
    </div>

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
                            <a class="brand" href="#">Messages</a>
                            <ul class="nav" id="myTab">
                            	<li class="active"><a href="#inbox">Inbox</a></li>
                                <li><a href="#sent">Sent</a></li>
                                <li><a href="#compose">Compose</a></li>
                                <li class="hide"><a href="#view">Compose</a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="tab-content">
                        <div class="tab-pane active" id="inbox">
                        	
                        	<div id="MessagesInbox" class="messages-inbox"></div>
                        
                        </div>
                        <div class="tab-pane" id="sent">
                        
                        	<div id="MessagesSent" class="messages-sent"></div>
                        
                        </div>
                        <div class="tab-pane" id="compose">
                        

                        	<div class="hero-unit share" style="padding: 30px 30px 60px 30px; background-position: 82px -35px;">
                            
                            	To:
                                <input type="text" id="message-to" placeholder="To:">
                                
                                Message:
                                <textarea id="message-text" required style="height:125px;"></textarea>
                                <button id="share-update-btn" class="btn btn-inverse pull-right" type="button" onClick="SendMessage();">Send Message</button>
                                
                            </div>

                        </div>
                        <div class="tab-pane" id="view">
                        	
                        	<h4 id="message-users"></h4>

                           	<div id="ViewMessageComplete" class="messages-view"></div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php includes("page.footer"); ?>

</body>
</html>