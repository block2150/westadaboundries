<?php

checkAccess();

?>



<html>
<head>
    <?php includes("page.meta"); ?>

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <link href="/css/global.css" rel="stylesheet" media="screen">

    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {
            swapNav('account');


            $("#invite-send").on("click", function()
            {
                $("#invite-success").hide();
                $("#invite-alert").hide();
                $("#invite-send").button('loading');
                var isValid = true;

                if (isValid)
                {
                    isValid = formValid($("#invite-name"), "name", $("#invite-message"), $("#invite-alert"), false, "current");
                }
                if (isValid)
                {
                    isValid = formValid($("#invite-email"), "email", $("#invite-message"), $("#invite-alert"), false, "new");
                }
                if (isValid)
                {
                    $.post('/api/user.invite',{
                            name: $("#invite-name").val(),
                            email: $("#invite-email").val()
                        },
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.status == "success") {
                                $("#invite-message-email").html($("#invite-email").val());
                                $("#invite-success").show();
								$("#invite-name").val("");
								$("#invite-email").val("");
                                $("#invite-send").button('reset');
                            } else {
                                $("#invite-message").html(json.message);
                                $("#invite-alert").show();
                                $("#invite-send").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#invite-send").button('reset');
                }
            })
        });

        function hideSuccess()
        {;
            $("#invite-success").hide();
            $("#invite-name").val("")
            $("#invite-email").val("")

        }

        //
        // Div Swapping
        //

    </script>

</head>
<body>

    <?php include_nav() ?>

    <div class="bg">
        <div id="page" class="container">
            <div class="row-fluid">
                <div class="span4">
                    <?php includes("side.portfolio"); ?>
                </div>
                <div class="span8 well">

                    <div class="tab-pane" id="invites">

                        <div class="hero-unit">

                            <h2>Invite Someone to Join</h2>

                            <p>Fotoluv.com is exclusivly for the modeling industry.  This exclusivity status helps us to maintain a level of quality not found on other social networking sites.  If you know someone who you think would like to join Fotoluv.com, use the form below to send them an invite.</p>

                        </div>

                        <div class="hero-content">

                            <form class="form-horizontal" autocomplete="off">
                                <div class="control-group">

                                    <div id="invite-alert" class="alert alert-error" style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('invite-alert');">&times;</button>
                                        <h4>Oops!</h4>
                                        <div id="invite-message"></div>
                                    </div>

                                    <div id="invite-success" class="alert alert-success"  style="display: none;">
                                        <button type="button" class="close" onClick="hideSuccess();">&times;</button>
                                        <h4>Thank You</h4>
                                        <div>We have sent an invite email and code to join Fotoluv.com to the following email address:  <span id="invite-message-email"></span></div>
                                    </div>

                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="invite-name">Name:</label>
                                    <div class="controls">
                                        <input type="text" id="invite-name" placeholder="Who would you like to invite?" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="invite-email">Email:</label>
                                    <div class="controls">
                                        <input type="text" id="invite-email" placeholder='What is their email address?' required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button type="button" id="invite-send" class="btn btn-large btn-danger" data-loading-text="Sending invitation...">Send Fotoluv.com Invite</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php includes("page.footer"); ?>

</body>
</html>