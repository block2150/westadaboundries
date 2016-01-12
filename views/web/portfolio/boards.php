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
    <link rel="stylesheet" media="screen" href="/css/bootstrapSwitch.css">

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script src="/js/jquery.timeago.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-button.js"></script>
    <script type="text/javascript" src="/js/bootstrapSwitch.js"></script>
    <script type="text/javascript" src="/js/jquery.date-format.js"></script>
    <script type="text/javascript" src="/js/hashchange.js"></script>
    
	<?php if ($_SESSION['user_contributor'] == "1") { ?>
    	<script src="/js/admin.js"></script>    
    <?php } ?>
    
    <script type="text/javascript" src="/js/global.js"></script>

    <script type="text/javascript" src="/js/load-image.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-image-gallery.js"></script>



    <script>
		var arrayBoards= "";
		var arrayBoardItems= "";
        $(document).ready(function() 
		{
			$(".container").css("width", "1200px");
			$("#logo-tag").html('<img src="/images/share-the-love.png" />');
			$("#QuickSearchResults").css("top", "0");
			$(".quick-search").css("margin-left", "210px");
			$(".notifications").css("margin-left", "800px");
			$(".notifications").css("top", "-12px");
			
			//portfolio/fcd07723-d9e8-11e2-9895-00ff893578ce/IMG_0217-Edit.jpg
			$.post('/api/boards.user.list',{
				},
				function(data) {
					arrayBoards = $.parseJSON(data);
					eventHandler(location.hash);
				}
			);
			//portfolio/fcd07723-d9e8-11e2-9895-00ff893578ce/IMG_0217-Edit.jpg
			$.post('/api/boards.items.list',{
				},
				function(data) {
					arrayBoardItems = $.parseJSON(data);
					eventHandler(location.hash);
				}
			);
			
			
			
		});
		$(window).hashchange( function(){
			eventHandler(location.hash);
		})
		
		function eventHandler(v)
		{
			var hash = v.replace(/^#/, '');
			if (v =="")
			{
				listBoards();
			}
			else
			{
				listBoardItems(hash);
			}
			
		}
		function listBoards()
		{
			$("#boards").html("");			
			$("#luv-title").html("Luv Boards");
			$("#luv-breadcrumb").empty();			
			$("#luv-breadcrumb").append('<li class="active"><a href="#">All Boards</a></li>');
			
			boardText = "boards";
			if (arrayBoards.length == 1)
			{
				boardText = "board";
			}
			
			$("#count").html(arrayBoards.length + ' luv ' + boardText);
			
			$.each(arrayBoards, function (index) {
				id = arrayBoards[index].id;
				board = arrayBoards[index].board;
				private = arrayBoards[index].private;
				created =  $.format.date(arrayBoards[index].created, "M/d/yyyy");
				image = '/portfolio/'+arrayBoards[index].source_id+'/'+arrayBoards[index].file_name;
				luvs = arrayBoards[index].luvs;
				
				//load portfolio gallery
				$('<div class="luv-wrapper link"/>')
					.append('<a href="#'+ id +'"><div class="luv-img-holder"><img src="' + image + '"></div></a>')
					.append('<a href="#'+ id +'"><div class="luv-board-details">' + board + '<br />' + luvs + ' Luvs</div></a>')
					.appendTo(boards);
			});	
		}
		
		function listBoardItems(v) 
		{
			$("#boards").html("");
			$("#luv-breadcrumb").empty();			
			$("#luv-breadcrumb").append('<li><a href="#">All Boards</a></li>');
			
			$.each(arrayBoards, function (index) {
				id = arrayBoards[index].id;
				board = arrayBoards[index].board;
				private = arrayBoards[index].private;
				created =  $.format.date(arrayBoards[index].created, "M/d/yyyy");
				image = '/portfolio/'+arrayBoards[index].source_id+'/'+arrayBoards[index].file_name;
				luvs = arrayBoards[index].luvs;
				
				if (id == v)
				{
					var luvText = "luvs";
					if (luvs == "1")
					{
						luvText = "luv";
					}
					
					$("#luv-title").html(board);
					$("#luv-breadcrumb").append('<li class="active"><a href="#' + id + '">' + board + '</a></li>');
					$("#count").html(luvs + ' ' + luvText);
				}
			});	
			
			$.each(arrayBoardItems, function (index) {
				id = arrayBoardItems[index].id;
				board_id = arrayBoardItems[index].board_id;
				board = arrayBoardItems[index].board;
				created =  $.format.date(arrayBoardItems[index].published, "M/d/yyyy");
				image = '/portfolio/'+arrayBoardItems[index].source_id+'/'+arrayBoardItems[index].file_name;
				comments = arrayBoardItems[index].comments;
				source = arrayBoardItems[index].source;
				source_id = arrayBoardItems[index].source_id;
				
				if (board_id == v)
				{
					//load portfolio gallery
					$('<div class="luv-wrapper link"/>')
						.append('<a href="/portfolio/' + source + '"><div class="luv-img-holder"><img src="' + image + '"></div></a>')
						.append('<a href="/portfolio/' + source + '"><div class="luv-board-details"><small>from the portfolio of</small><br />' + source + '</div></a>')
						.appendTo(boards);
				}
			});	
		}
    </script>

</head>
<body>

    <?php include_nav() ?>

    
    
    <div id="boards-fullscreen">
    
    	<div class="text-center white">
    		<h1 id="luv-title">Luv Boards</h1>
    	</div>
    
        <div data-toggle="modal-gallery" data-target="#modal-gallery" class="well">
        
            <div class="navbar navbar-inverse">
              <div class="navbar-inner">
                <a class="brand" href="#">Luv Boards</a>
                <ul id="luv-breadcrumb" class="nav">
                  <li class="active"><a href="#">All Boards</a></li>
                </ul>
                <div id="count" class="pull-right"></div>
              </div>
            </div>
        	<div id="boards" class="columns-boards hero-unit"></div>
        </div>
        
    </div>

    <?php includes("page.footer"); ?>
</body>
</html>