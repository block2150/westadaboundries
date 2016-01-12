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

            swapNav('account')

            $("#account-save-info").on("click", function()
            {
                $("#info-success").hide();
                $("#info-alert").hide();
                $("#account-save-info").button('loading');
                var isValid = true;

                if (isValid)
                {
                    isValid = formValid($("#account-email"), "email", $("#info-message"), $("#info-alert"), false);
                }
                if (isValid)
                {
                    $.post('/api/user.info.set',{
                            email: $("#account-email").val()
                        },
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.status == "success") {
                                $("#info-success").show();
                                $("#account-save-info").button('reset');
                            } else {
                                $("#info-message").html(json.message);
                                $("#info-alert").show();
                                $("#account-save-info").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#account-save-info").button('reset');
                }
            })

            $("#account-save-password").on("click", function()
            {
                $("#info-success").hide();
                $("#info-alert").hide();
                $("#account-save-password").button('loading');
                var isValid = true;

                if (isValid)
                {
                    isValid = formValid($("#account-current-password"), "password", $("#password-message"), $("#password-alert"), false, "current");
                }
                if (isValid)
                {
                    isValid = formValid($("#account-new-password"), "password", $("#password-message"), $("#password-alert"), false, "new");
                }
                if (isValid)
                {
                    isValid = formValid($("#account-confirm-password"), "password", $("#password-message"), $("#password-alert"), false, "confirm");
                }
                if ($("#account-new-password").val() != $("#account-confirm-password").val())
                {
                    $("#password-message").html("It looks like your passwords do not match.  Please make sure you take note of your new password so you don't have any problems in the future.")
                    $("#password-alert").show();
                    isValid = false;
                }
                if (isValid)
                {
                    $.post('/api/user.password.set',{
                            current: $("#account-current-password").val(),
                            new: $("#account-new-password").val()
                        },
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.status == "success") {
                                $("#password-success").show();
                                $("#account-save-password").button('reset');
                            } else {
                                $("#password-message").html(json.message);
                                $("#password-alert").show();
                                $("#account-save-password").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#account-save-password").button('reset');
                }
            })

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
        });


        //
        // Div Swapping
        //

    </script>

</head>
<body>
account-email
<?php include_nav() ?>

<div class="bg">
    <div id="page" class="container">
        <div class="row-fluid">
            <div class="span4">
                <?php includes("side.portfolio"); ?>
            </div>
            <div class="span8 well">

                <div class="navbar">
                    <div class="navbar-inner">
                        <a class="brand" href="#">Your Account</a>
                        <ul class="nav" id="myTab">
                            <li class="active"><a href="#details">Details</a></li>
                            <li><a href="#billing">Billing</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#upgrade">Upgrade</a></li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="details">

                        <div class="hero-unit">

                            <h2>Account Information</h2>

                            <p>User the form below to manage your basic Fotoluv.com account information.  Keeping this information up-to-date will make sure that we can better assist you in promoting your portfolio and connecting with others.</p>

                        </div>

                        <div class="hero-content">

                            <form class="form-horizontal" autocomplete="off">
                                <div class="control-group">

                                    <div class="page-header">
                                        <h3>Basic Infomation <small>All are required</small></h3>
                                    </div>

                                    <div id="info-alert" class="alert alert-error" style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('info-alert');">&times;</button>
                                        <h4>Oops!</h4>
                                        <div id="info-message"></div>
                                    </div>

                                    <div id="info-success" class="alert alert-success"  style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('info-success');">&times;</button>
                                        <h4>Thank You</h4>
                                        <div>Hey, thanks for updating your information.  It has been successfully saved in our system.</div>
                                    </div>

                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Username:</label>
                                    <div class="controls">
                                        <span id="account-username" class="uneditable-input"><?php echo $_SESSION['user_username']; ?></span>
                                        <small class="help-block">Your public portfolio is located at <a href="/">http://fotoluv.com/<?php echo $_SESSION['user_username']; ?></a></small>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Email:</label>
                                    <div class="controls">
                                        <input type="text" id="account-email" placeholder="Email Address" required value="<?php echo $_SESSION['user_email']; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button type="button" id="account-save-info" class="btn btn-large btn-danger" data-loading-text="Saving your informamtion...">Save Basic Information</button>
                                    </div>
                                </div>
                                <div class="control-group">

                                    <div class="page-header">
                                        <h3>Change Your Password <small>All are required</small></h3>
                                    </div>

                                    <li>Passwords are case-sensitive and must be at least 6 characters.</li>
                                    <li>A good password should contain a mix of capital and lower-case letters, numbers and symbols.</li>

                                    <br />

                                    <div id="password-alert" class="alert alert-error" style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('password-alert');">&times;</button>
                                        <h4>Oops!</h4>
                                        <div id="password-message"></div>
                                    </div>

                                    <div id="password-success" class="alert alert-success"  style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('password-success');">&times;</button>
                                        <h4>Thank You</h4>
                                        <div>Your password has been successfully changes.  Please let us know if you have any trouble in the future.</div>
                                    </div>

                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Current:</label>
                                    <div class="controls">
                                        <input type="password" id="account-current-password" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">New:</label>
                                    <div class="controls">
                                        <input type="password" id="account-new-password" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Confirm:</label>
                                    <div class="controls">
                                        <input type="password" id="account-confirm-password" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button type="button" id="account-save-password" class="btn btn-large btn-danger" data-loading-text="Saving changes...">Save Your Password Changes</button>
                                    </div>
                                </div>
                            </form>


                        </div>

                    </div>
                    <div class="tab-pane" id="billing">

                        <div class="hero-unit">

                            <h2>Coming Soon...</h2>

                            <p>This section will contain information about your billing settings that Fotoluv.com will use to process your monthly service charges.</p>

                        </div>

                        <div class="hero-content">
                            <?php includes("page.future.features"); ?>
                        </div>

                    </div>
                    <div class="tab-pane" id="services">

                        <div class="hero-unit">

                            <h2>Coming Soon...</h2>

                            <p>This section will provide you with detail information on additional services provided by Fotoluv.com.</p>

                        </div>

                        <div class="hero-content">
                            <?php includes("page.future.features"); ?>
                        </div>

                    </div>
                    <div class="tab-pane" id="upgrade">

                        <div class="hero-unit">

                            <h2>Coming Soon...</h2>

                            <p>Here you will be able to manage your subscription levels, so you can take advantage of all Fotoluv.com has to offer.</p>

                        </div>

                        <div class="hero-content">
                            <?php includes("page.future.features"); ?>
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