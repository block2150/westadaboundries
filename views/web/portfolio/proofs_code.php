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
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {
            swapNav('account');


            $("#code-send").on("click", function()
            {
                $("#code-success").hide();
                $("#code-alert").hide();
                $("#code-send").button('loading');
                var isValid = true;

                if (isValid)
                {
                    isValid = formValid($("#code-name"), "name", $("#code-message"), $("#code-alert"), false, "current");
                }
                if (isValid)
                {
                    isValid = formValid($("#code-email"), "email", $("#code-message"), $("#code-alert"), false, "new");
                }
                if (isValid)
                {
                    $.post('/api/proofs.code',{
                            name: $("#code-name").val(),
                            email: $("#code-email").val()
                        },
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.status == "success") {
                                $("#code-message-email").html($("#code-email").val());
                                $("#code-success").show();
								$("#code-name").val("");
								$("#code-email").val("");
                                $("#code-send").button('reset');
                            } else {
                                $("#code-message").html(json.message);
                                $("#code-alert").show();
                                $("#code-send").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#code-send").button('reset');
                }
            })
        });

        function hideSuccess()
        {;
            $("#code-success").hide();
            $("#code-name").val("")
            $("#code-email").val("")

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

                    <div class="tab-pane" id="codes">

                        <div class="hero-unit">

                            <h2>Get a Proofs Upload Code</h2>

                            <p>Fotoluv.com has made it esay for you to recieve proofs from different photograpers and have them added to your portfolio.  No more need for crazy emails trying to get proofs back, we make it easy.  Just use the form below to send a one time upload code and let us do the rest.</p>

                        </div>

                        <div class="hero-content">

                            <form class="form-horizontal" autocomplete="off">
                                <div class="control-group">

                                    <div id="code-alert" class="alert alert-error" style="display: none;">
                                        <button type="button" class="close" onClick="hideAlert('code-alert');">&times;</button>
                                        <h4>Oops!</h4>
                                        <div id="code-message"></div>
                                    </div>

                                    <div id="code-success" class="alert alert-success"  style="display: none;">
                                        <button type="button" class="close" onClick="hideSuccess();">&times;</button>
                                        <h4>Thank You</h4>
                                        <div>Your upload code has been sent to:  <span id="code-message-email"></span></div>
                                    </div>

                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="code-name">Name:</label>
                                    <div class="controls">
                                        <input type="text" id="code-name" placeholder="Who would you like to send the upload code to?" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="code-email">Email:</label>
                                    <div class="controls">
                                        <input type="text" id="code-email" placeholder='What is their email address?' required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button type="button" id="code-send" class="btn btn-large btn-danger" data-loading-text="Sending Code...">Send Fotoluv.com Upload Code</button>
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