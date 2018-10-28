<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Sendmail {

    public static $errorLog = "";

    public static function sendEmailFormated($email, $emailname, $subject, $content) {
        global $config;

        $mail_header = $config["email"]["content"]["header"];

        $mail_footer = $config["email"]["content"]["footer"];

        $content1 = $mail_header.$content.$mail_footer;

        return self::sendEmail($email, $emailname, $subject, $content1, $content1);
    }

    public static function sendEmail($email, $emailname, $subject, $content, $contenthtml = '') {

        global $config;

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $config["smtp"]["host"];                // Specify main and backup SMTP servers
            $mail->SMTPAuth = $config["smtp"]["auth"];            // true to enable SMTP authentication
            $mail->Username = $config["smtp"]["username"];        // SMTP username
            $mail->Password = $config["smtp"]["password"];        // SMTP password
            $mail->SMTPSecure = $config["smtp"]["sec"];           // 'tls' enable TLS encryption, `ssl` also accepted
            $mail->Port = $config["smtp"]["port"];                // f.e. 587 TCP port to connect to

            //Recipients
            $mail->setFrom($$config["smtp"]["from"], $config["smtp"]["fromName"]);
            $mail->addAddress($email, $emailname);                // Add a recipient
            if (isset($config["smtp"]["replyto"])) {
                $mail->addReplyTo($config["smtp"]["replyto"], $config["smtp"]["replytoname"]);
            }
            if (isset($config["smtp"]["cc"])) {
                $mail->addCC($config["smtp"]["cc"]);
            }
            if (isset($config["smtp"]["bcc"])) {
                $mail->addBCC('bcc@example.com');
            }
            
            //Content
            if (strlen($contenthtml) > 0) {
                $mail->isHTML(true);                                  // Set email format to HTML
            }
            $mail->Subject = $subject;
            if (strlen($contenthtml) > 0) {
                $mail->Body    = $contenthtml;
            } else {
                $mail->Body    = $content;
            }

            $mail->AltBody = $content;

            $mail->send();
            return true;
        } catch (Exception $e) {
            self::$errorLog = $e->getMessage();
            self::$errorLog .= $mail->ErrorInfo;
            error_log($e->getMessage().":".$mail->ErrorInfo." / email: ".$email ." / sub: ".$subject);
            return false;
        }
    }

}

