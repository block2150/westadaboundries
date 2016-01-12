<!DOCTYPE html>
<html>
<head>
    <title>Fotoluv.com QA Testing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="/css/global.css" rel="stylesheet" media="screen">
    <link href="/css/bootstrapSwitch.css" rel="stylesheet" media="screen">

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/bootstrap-tab.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>

    <style>
        .well { background-color: #fff; }

        .hero-unit {
            font-size: 13px;
            font-weight: normal;
            line-height: 26px;
            margin-bottom: 30px;
            padding: 15px;
        }

        .indent {
            padding-left: 38px;
        }

        .log_m {
            margin-bottom: 5px;
        }

        #verbose-container {
            width: 160px;
        }

        #verbosetitle {
            padding-top 10px;
            float: left;
        }
        #verbose {
            margin-top: 8px;
            float: right;
        }
    </style>

    <script>

        $(document).ready(function() {

            $('#verbose').on('switch-change', function () {
                $("div.test-footer").toggle();
                $("div.test-header").toggle();
                $("#test-list").toggle();
            });


        });

    </script>

</head>
<body>

<div class="container-fluid">

    <br />

    <div class="row-fluid">
        <div class="span12 well">


