<?php
/**
 * 发送邮件
 */
class_exists('Typecho_Widget') or die('This file can not be loaded directly.');

class_exists('PHPMailer') or require('AjaxComments/class.phpmailer.php');

$phpmailer = new PHPMailer();

if ($smtp_enable) {
    $phpmailer->IsSMTP();
    $phpmailer->SMTPAuth   = $smtp_auth;
    $phpmailer->SMTPSecure = $smtp_secure;
    $phpmailer->Host       = $smtp_host;
    $phpmailer->Port       = $smtp_port;
    $phpmailer->Username   = $smtp_username;
    $phpmailer->Password   = $smtp_password;
}

  $phpmailer->AddAddress($to);
  $phpmailer->Body = $message;
  $phpmailer->CharSet = 'UTF-8';
  $phpmailer->FromName = $fromname;
  $phpmailer->From = $from;
  $phpmailer->IsHTML(true);
  $phpmailer->Subject = $subject;
  $phpmailer->Send(); // 寄出
