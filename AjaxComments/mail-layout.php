<?php
/**
 * 邮件內容
 */
class_exists('Typecho_Widget') or die('This file can not be loaded directly.');

$prv_author   = $parent['author'];      // 被回复者
$prv_text     = nl2br($parent['text']); // 被回复內容
$post_title   = $comment->title;        // 文章标题
$reply_author = $comment->author;       // 回复者
$reply_text   = nl2br($comment->text);  // 回复內容
$permalink    = $comment->permalink;    // 新评论鏈接
$home_url     = $options->siteUrl;      // 博客鏈接
$blogname     = $options->title;        // 博客名称

// 邮件排版在下面, 请自行修改:
$border = 'border-radius:5px';
$message = "
<div style='background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-$border; -webkit-$border; -khtml-$border; $border;'>
  <p>" . $prv_author . ", 您好!</p>
  <p>您曾在《" . $post_title . "》的留言:<br/>"
   . $prv_text . "</p>
  <p>" . $reply_author . " 给您的回复:<br/>"
   . $reply_text . "<br/></p>
  <p>您可以点击 <a href='" . $permalink . "'>查看回复完整内容</a></p>
  <p>欢迎再度光临 <a href='" . $home_url . "'>" . $blogname . "</a></p>
  <p>(此邮件由系统自动发出, 请勿回复.)</p>
</div>";

// 标题及发件信息
$subject = '您在 ['. $blogname .'] 的评论有了回复';
$fromname = '=?UTF-8?B?'. base64_encode($blogname) .'?=';
$from = 'no-reply@'. preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));


/* 服务器若没开 mail(), 可使用 SMTP 代发,
 * 若要开啟 SMTP 功能, 请修改以下变量.
 * 服务器可使用 mail() 的就別改了.
 */
$smtp_enable   = 0;                // SMTP 开关( 0:关, 1:开 ), 默认为 0 使用 mail().
$smtp_auth     = true;             // SMTP 是否要身份验证, 一般都要.
$smtp_secure   = 'ssl';            // 使用 SSL 加密連接, 若沒有 SSL, 留空 ''.
$smtp_host     = 'smtp.gmail.com'; // SMTP 服務器.
$smtp_port     = 465;              // SMTP 服務器端口, 一般为 25, gmail 为 465.
$smtp_username = 'xxxx@gmail.com'; // 用戶名, 一般为邮箱名.
$smtp_password = 'xxxxxxxxxx';     // 登入密码.
