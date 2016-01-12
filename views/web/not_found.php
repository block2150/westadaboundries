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
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {


            swapNav('<?php echo $path[1] ?>')
        });


        //
        // Div Swapping
        //

    </script>
    
    <style>
		.navbar .nav {
			margin: 5px 10px 0 0;
		}
		.navbar-inverse .navbar-inner
		{
			top: 0;
		}
	</style>

</head>
<body>

<?php include_nav() ?>

<div class="bg">
    <div id="page" class="container">
        <div class="row-fluid">
            <div id="home-logo" class="span6 text-center"><img src="/images/logo.png" /></div>
            <div class="span6">

                <img src="/images/motto.png" />

                <div class="motto-content">
                    <div class="well">

                        <h3>Portfolio Not Found</h3>

                        The portfolio you are looking for is not found.  Plesae check your information and try again.  If you are new to Fotoluv.com use the navigation above to take a look around.
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php includes("page.footer"); ?>
</body>
</html>