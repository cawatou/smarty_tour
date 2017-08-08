<?php

DxFactory::import('Utils');

class Utils_Mail
{
    const ERROR_BASE      = 2000;
    const ERROR_MAIL_SEND = 2001;

    public function __construct()
    {
        include_once('PHPMailer/class.phpmailer.php');
    }

    public function send($to, $from, $subject, $body, $is_html = false, $attach = null, $reply = null)
    {
        $config = DxApp::config('mail');

    	if (empty($to)) {
    	    $to = $config['default_to'];
    	}
    	if (empty($from)) {
    	    $from = $config['default_from'];
    	}

        $mail = new PHPMailer();

        if (!empty($config['smtp'])) {
            $mail->IsSMTP();                              // telling the class to use SMTP
            $mail->SMTPDebug  = 2;                        // enables SMTP debug information (for testing)
            // 1 = errors and messages
            // 2 = messages only
            $mail->SMTPAuth   = $config['smtp']['auth'];   // enable SMTP authentication
            $mail->SMTPSecure = $config['smtp']['secure']; // sets the prefix to the servier
            $mail->Host       = $config['smtp']['host'];   // sets GMAIL as the SMTP server
            $mail->Port       = $config['smtp']['port'];   // set the SMTP port for the GMAIL server
            $mail->Username   = $config['smtp']['user'];   // GMAIL username
            $mail->Password   = $config['smtp']['pass'];   // GMAIL password
        }

        if (!empty($config['charset'])) {
            $mail->CharSet = $config['charset'];
        }
        if (!empty($config['encoding'])) {
            $mail->Encoding = $config['encoding'];
        }
		
        if ($is_html) {
            $mail->IsHTML(true);
        }		

        $mail->SetFrom($from);
        if (!empty($reply)) {
            $mail->AddReplyTo($reply);
        }
        $to = (array)$to;
        foreach ($to as $_to) {
            $mail->AddAddress($_to);
        }

        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!is_null($attach)) {
            $attach = (array)$attach;
            foreach ($attach as $_attach) {
                $mail->AddAttachment($_attach);
            }
        }

        if (!$mail->Send()) {
            throw new DxException("Can not send mail: {$mail->ErrorInfo}", self::ERROR_MAIL_SEND);
        }
        return true;
    }

    public static function textOfTemplate($template, $data)
    {
        $smarty = DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY, true);
        $smarty->assign(array(
            'data' => $data,
        ));
        return $smarty->fetch($template);
    }
}