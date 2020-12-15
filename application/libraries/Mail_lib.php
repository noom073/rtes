<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail_lib
{

    public function __construct()
    {}

    public function send_register_detail($idp, $email, $message)
    {
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->SMTPDebug    = 0;
        $mail->SMTPAuth     = true;
        $mail->SMTPSecure   = "tls";
        $mail->SMTPOptions  = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
        $mail->Host         = "intmail.rtarf.mi.th";
        $mail->Port         = 587;
        $mail->Username     = "mildoc@rtarf.mi.th";
        $mail->Password     = "xje7Cjma";
        $mail->SetFrom("rtarfli@rtarf.mi.th", 'สถาบันภาษากองทัพไทย RTARF Language Institute');

        $text = "<h2>บัตรประจำตัวผู้เข้าสอบ</h2> <h3>การทดสอบวัดระดับทักษะภาษาอังกฤษของ บก.ทท.</h3>";

        $mail->AddAddress($email);
        $file_name = FCPATH . "/assets/PDF_generate/{$idp}.pdf";
        $mail->addAttachment($file_name);
        $mail->Subject = 'RTARF Language Institute: สถาบันภาษากองทัพไทย';
        $mail->MsgHTML($text);
        $mail->CharSet = "utf-8";

        //send the message, check for errors
        if (!$mail->send()) {
            // echo "Mailer Error: " . $mail->ErrorInfo;
            $send['status'] = false;
            $send['text']   = "ส่ง Email ไม่สำเร็จ Mailer Error: " . $mail->ErrorInfo;
        } else {
            // echo "Message sent!";
            $send['status'] = true;
            $send['text']   = "ส่ง Email สำเร็จ";
        }

        return $send;
    }
}
