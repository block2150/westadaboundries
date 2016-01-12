<?php

function checkAccess()
{
    if ($_SESSION['user_id'] == "")
    {
        header("Location: /");
    }
}

function getTypeName($type)
{
	switch ($type) {
		case 1;
			return "Model";
			break;
		case 2;
			return "Photographer";
			break;
		case 3;
			return "Makeup Artist";
			break;
		case 4;
			return "Wardrobe";
			break;
		case 5;
			return "Hair Stylist";
			break;
		case 7;
			return "Retoucher";
			break;
		case 9;
			return "Artist/Painter";
			break;
		case 19;
			return "Body Painter";
			break;
		case 20;
			return "Clothing Designer<";
			break;
		case 24;
			return "Digital Artist";
			break;
	}
}