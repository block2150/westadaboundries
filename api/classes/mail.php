<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/classes/PHPMailer/class.phpmailer.php");

class mail {

    public $from;
    public $fromName;
    public $to;
    public $suject;
    public $bodyPath;
    public $arrayKV;

    function Send() {

        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');

        //Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->IsSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug  = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host       = 'smtp.gmail.com';
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port       = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth   = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username   = "block2150@gmail.com";
        //Password to use for SMTP authentication
        $mail->Password   = "1!bcdd$1";
        //Set who the message is to be sent from
        $mail->SetFrom($this->from, $this->fromName);

        //Set who the message is to be sent to
        $mail->AddAddress($this->to);
        //Set the subject line
        $mail->Subject = $this->suject;
        //Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body

        $messageString = file_get_contents($_SERVER['DOCUMENT_ROOT'].$this->bodyPath);
        $messageString = str_replace(array_keys($this->arrayKV), $this->arrayKV, $messageString);


        $mail->MsgHTML($messageString);
        $mail->AltBody = $messageString;

        //Send the message, check for errors
        if(!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
            return "success";
        }
    }
}