<?php
/**
 * Fotolov.com Public Portfolio Templatates
 * Name: Default Public Portfolio
 * Type: Basic
 * Author: Ryan Riley
 * Desc: This public portfolio template is a simple and easy to follow that has a basic image slider and full frame portfolio view.
 */
?>

<html>
<head>
    <?php includes("page.meta"); ?>

    <!-- Bootstrap -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/global.js"></script>
    <script type="text/javascript" src="/js/galleria/galleria-1.2.9.js"></script>
    <script>

        $(document).ready(function() {
            loadData();
            $("#galleria").show();
        });

        function loadData() {

            $.post('/api/portfolio.images.list',{
                    id: '<?php echo $_SESSION['site_user_id']; ?>',
					category_id: 1
                },
                function(data) {
                    var json = $.parseJSON(data);
                    if (json.status == "failed") {
                    }
                    else
                    {
                        var json = $.parseJSON(data);
                        $.each(json, function (index) {
							if (json[index].file_name != "")
							{
								icon = "/portfolio/" + json[index].user_id + "/icon_" + json[index].file_name;
								thumb = "/portfolio/" + json[index].user_id + "/thumb_" + json[index].file_name;
								profile = "/portfolio/" + json[index].user_id + "/profile_" + json[index].file_name;
								url = "/portfolio/" + json[index].user_id + "/" + json[index].file_name;
								desc = json[index].description;
	
								//load portfolio gallery
								$('<img>')
									.prop('src', url)
									.prop('width', '250')
									.prop('data-description', desc)
									.appendTo(galleria);
	
								preload([url]);
							}
                        });
                    }
                }
            );
        }

    </script>
    <style>
        body {background: #000; margin: 0; padding: 0;}
        img {border: 0}
        #galleria{ width: 100%; height: 100%; background: #000 }
    </style>

</head>
<body>

    <div id="motto" style="background: transparent; position: absolute; z-index: 1000; right: 10px; top: 10px;"><a href="/"><img src="/images/motto.png" width="160" /></a></div>

    <div id="galleria" style="display: none;"></div>

    <script>
        Galleria.loadTheme('/js/galleria/themes/azur/galleria.azur.min.js');
        Galleria.run('#galleria', {
            imageCrop: 'landscape'
        });
    </script>

    <!-- qa:fotolove.com -->
</body>
</html>