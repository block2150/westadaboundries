<html>
<head>
    <?php includes("page.page.meta"); ?>

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="/css/dropzone.css" rel="stylesheet" media="screen">
    <link href="/css/global.css" rel="stylesheet" media="screen">

    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/dropzone.js"></script>
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {

            $("#button-code").on("click", function()
            {
                $("#button-code").button('loading');
                var isValid = true;

                if ($("#home-upload-code").val() == "")
                {
                    $("#alert-message").html("Please enter an upload code.  If you don't have an upload code you will need to contact the Fotoluv.com member you are working with to request one.");
                    $("#home-alert").show();
                    isValid = false;
                }
                if (isValid)
                {
                    $.post('/api/proofs.code.validate',{
                            code: $("#upload-code").val()
                        },
                        function(data) {
                            var json = $.parseJSON(data);
                            if (json.status == "success") {
                                showUpload();
                            } else {
                                $("#alert-message").html(json.message);
                                $("#home-alert").show();
                                $("#button-code").button('reset');
                            }

                        }
                    );
                }
                else
                {
                    $("#button-code").button('reset');
                }
                return false;
            });

            swapNav('<?php echo $path[1] ?>');

        });



        function showUpload()
        {
            $("#home-alert").hide();
            $("#home-success").hide();
            $("#validate-form").hide();
            $("#upload-form").show();
        }
    </script>

</head>
<body>

    <?php include_nav() ?>

    <div class="bg">
        <div id="page" class="container">
            <div class="row-fluid">

                <div id="validate-form">
                    <div id="home-logo" class="span6 text-center"><img src="/images/logo.png" /></div>
                    <div class="span5">

                        <img src="/images/motto.png" />

                        <div id="home-alert" class="alert alert-error" style="display: none;">
                            <h4>Oops!</h4>
                            <div id="alert-message"></div>
                        </div>


                        <form id="home-code" class="home-form"" autocomplete="off">

                            <input type="text" id="upload-code" name="code" placeholder="Enter your upload code" required>

                            <p>You have been trusted with a special privlage to upload photos to a specific Fotoluv.com portfolio.  Any abuse of this privalage may result in being band from Fotoluv.com.  If you have any questions about this process please contact Fotoluv.com support or the person you received the upload code email from.

                            <p><button id="button-code" class="btn btn-large btn-block btn-danger" type="button" data-loading-text="Validating your code...">VALIDATE YOUR UPLOAD CODE</button></p>

                        </form>
                    </div>
                </div>

                <div id="upload-form" class="hide">

                    <div class="span4 well">
                        <img src="/images/logo.png" id="profile-image" class="img-polaroid">
                    </div>

                    <div class="span8 well">


                        <div class="hero-unit">

                            <h2>Upload Portfolio Proofs</h2>
                            <p>Uploading proofs to Fotluv.com is as easy as drag and drop.  All you need to do is drag the photos you would like to upload to the area below.  They will be associated with the portfolio for the upload code you received.</p>

                        </div>


                        <div class="hero-content">

                            <span class="label label-important">Important</span> Your images must be smaller then 2 MB in size.  Also remember impact is everything.  An image that will sized to 1200px by 800px will fill the screen and have more impact.

                            <br />
                            <form action="/module/proofs.images.upload.php" class="dropzone" id="protfolio-dropzone"></form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>






    <?php includes("page.footer"); ?>
</body>
</html>