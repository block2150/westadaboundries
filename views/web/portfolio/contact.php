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
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script src="/js/global.js"></script>

    <script>

        $(document).ready(function() {


            swapNav('contact')
        });


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
            <div id="home-logo" class="span6 text-center"><img src="/images/logo.png" /></div>
            <div class="span6">

                <img src="/images/motto.png" />

            </div>
        </div>
    </div>
</div>

<?php includes("page.footer"); ?>
</body>
</html>