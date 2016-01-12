<?php

require_class("proofs.php");

$code = $_POST["code"];

$Proofs = new Proofs($code);

if ($Proofs->status == "0")
{
    $Proofs->status = 1;
    $Proofs->update();
	
    $_SESSION['upload_id'] = $Proofs->by;
	
    echo '{"status":"success","message":"Your invite code has been validated"}';
}
else
{
    if ($Proofs->status == "1")
    {
        echo '{"status":"failed","message":"Sorry but the uplaod code you entered has already been used.  Please check your information and try again.  If you keep having this problem, you might need to ask for a different upload code."}';
    }
    else
    {
        echo '{"status":"failed","message":"Sorry but there was a problem with the upload code you entered.   Please check your code and try again."}';
    }
}

