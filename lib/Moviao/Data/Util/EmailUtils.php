<?php
declare(strict_types=1);
namespace Moviao\Data\Util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailUtils {

    //private const MAIL_SERVER = 'mail.moviao.com';              // Mail Server
    //private const MAIL_SERVER2 = 'mx4.mail.ovh.net';            // Mail Server 2
    //private const MAIL_SERVER3 = 'mx3.mail.ovh.net';            // Mail Server 3
    private const MAIL_SERVER_LOCAL = 'moviao.com';              // Mail Server Local  46.105.99.14

    private const SENDER_EMAIL = 'info@moviao.com';             // Sender Email
    private const SMTP_PWD = '0N+,Nnr76@$dyG/U!C/?NQmQmkftA';   // SMTP Password
    private const DKIM_SERVER   = 'moviao.com';                 // Dkim Domain
    private const DKIM_PRIVATE  = './dkim/moviao.com.pem';      // Dkim Private key
    private const DKIM_SELECTOR = 'dkim';                       // Dkim Selector

    public function sendEmail(string $address,string $template,array $form) : bool {
        $result = false;
        try {
            //Load Composer's autoloader
            require './package/phpmailer/vendor/autoload.php';

            $xml = simplexml_load_file($template,"SimpleXMLElement",LIBXML_NOCDATA);

            //---------------------------
            $body = (String) $xml->Body;
            //error_log('body exxxxxxxxxxxxxx : ' . $body);

            if (!is_null($form)) {
                if (count($form) > 0) {
                    foreach ($form as $key => $value) {
                        $body = preg_replace('(%' . $key . '%)', $value, $body);
                    }
                }
            }
            //---------------------------
            $subject = (String) $xml->Subject;
            if (!empty($form) && is_iterable($form)) {
                if (count($form) > 0) {
                    foreach ($form as $key => $value) {
                        $subject = preg_replace('(%' . $key . '%)', $value, $subject);
                    }
                }
            }
            //---------------------------
            $replyTo = (String) $xml->ReplyTo;
            if (!is_null($form)) {
                if (count($form) > 0) {
                    foreach ($form as $key => $value) {
                        $replyTo = preg_replace('(%' . $key . '%)', $value, $replyTo);
                    }
                }
            }

            //error_log('replyTo insert value tchatchatcha : ' . $replyTo);

            //---------------------------
            $replyToName = (String) $xml->ReplyToName;
            if (!is_null($form)) {
                if (count($form) > 0) {
                    foreach ($form as $key => $value) {
                        $replyToName = preg_replace('(%' . $key . '%)', $value, $replyToName);
                    }
                }
            }

            //---------------------------
            $mail = new PHPMailer();
            //$mail->SMTPDebug = 3;                           // Enable verbose debug output
            $mail->isSMTP();                                  // Set mailer to use SMTP
            $mail->Host = self::MAIL_SERVER_LOCAL;                  // Specify main and backup SMTP servers   // postfix
            $mail->SMTPAuth = false;                           // Enable SMTP authentication
            $mail->Username = self::SENDER_EMAIL;             // SMTP username
            $mail->Password = self::SMTP_PWD;                 // SMTP password
            //$mail->SMTPSecure = 'tls';                        // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 1587;                                // TCP port to connect to
            $mail->From = $xml->From;
            $mail->FromName = $xml->FromName;
            //$mail->addAddress('djamil.hammouche@gmail.com', 'Joe User');     // Add a recipient
            $mail->addAddress($address);                            // Name is optional
            $mail->addReplyTo($replyTo, $replyToName); // $xml->ReplyTo   $xml->ReplyToName
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->Encoding = 'quoted-printable';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);                             // Set email format to HTML
            $mail->Subject = $subject; //$xml->Subject;
            $mail->Body = $body;
            $mail->AltBody = $xml->AltBody;

            // Correction bug tls
//            $mail->SMTPOptions = array(
//                'ssl' => array(
//                    'verify_peer' => false,
//                    'verify_peer_name' => false,
//                    'allow_self_signed' => true
//                )
//            );

            //This should be the same as the domain of your From address
            $mail->DKIM_domain = self::DKIM_SERVER;
            //See the DKIM_gen_keys.phps script for making a key pair -
            //here we assume you've already done that.
            //Path to your private key:
            $mail->DKIM_private = self::DKIM_PRIVATE;
            //Set this to your own selector
            $mail->DKIM_selector = self::DKIM_SELECTOR;
            //Put your private key's passphrase in here if it has one
            $mail->DKIM_passphrase = '';
            //The identity you're signing as - usually your From address
            $mail->DKIM_identity = $mail->From;

            if ($mail->send()) {
                //echo 'Message has been sent';
                $result = true;
            } else {
                //echo 'Message could not be sent.';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
                error_log("EmailUtils >> sendEmail (false) \r\n" . $mail->ErrorInfo);
                $result = false;
            }

        } catch (\Throwable $e) {
            error_log("EmailUtils >> sendEmail $e \r\n"); // libxml_get_errors()
        }

        return $result;
    }


    public function sendEmail2(string $from, string $to,string $subject, string $body) : bool {
        $result = false;
        try {

            //Load Composer's autoloader
            require './package/phpmailer/vendor/autoload.php';

            $mail = new PHPMailer();
            $mail->SMTPDebug = 3;                           // Enable verbose debug output
            $mail->isSMTP();                                  // Set mailer to use SMTP
            $mail->Host = self::MAIL_SERVER_LOCAL;                  // Specify main and backup SMTP servers   // postfix
            $mail->SMTPAuth = false;                          // Enable SMTP authentication
            $mail->Username = self::SENDER_EMAIL;             // SMTP username
            $mail->Password = self::SMTP_PWD;                 // SMTP password
            //$mail->SMTPSecure = 'tls';                        // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 1587;                                // TCP port to connect to
            $mail->From = $from;
            $mail->FromName = $from;
            //$mail->addAddress('djamil.hammouche@gmail.com', 'Joe User');     // Add a recipient
            $mail->addAddress($to);                            // Name is optional
            $mail->addReplyTo($to, $to);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->Encoding = 'quoted-printable';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);                             // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $body;

            // Correction bug tls
//            $mail->SMTPOptions = array(
//                'ssl' => array(
//                    'verify_peer' => false,
//                    'verify_peer_name' => false,
//                    'allow_self_signed' => true
//                )
//            );

            //This should be the same as the domain of your From address
            //$mail->DKIM_domain = self::DKIM_SERVER;
            //See the DKIM_gen_keys.phps script for making a key pair -
            //here we assume you've already done that.
            //Path to your private key:
            //$mail->DKIM_private = self::DKIM_PRIVATE;
            //Set this to your own selector
            //$mail->DKIM_selector = self::DKIM_SELECTOR;
            //Put your private key's passphrase in here if it has one
            //$mail->DKIM_passphrase = '';
            //The identity you're signing as - usually your From address
            //$mail->DKIM_identity = $mail->From;

            if ($mail->send()) {
                //echo 'Message has been sent';
                $result = true;
            } else {
                //echo 'Message could not be sent.';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
                error_log("EmailUtils >> sendEmail2 (false) \r\n" . $mail->ErrorInfo);
                $result = false;
            }

        } catch (\Throwable $e) {
            error_log("EmailUtils >> sendEmail2 $e \r\n"); // . libxml_get_errors());
        }

        return $result;
    }


}