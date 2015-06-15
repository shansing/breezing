<?php
class_exists('Typecho_Widget') or die('This file can not be loaded directly.');

$options = Typecho_Widget::widget('Widget_Options');
$pic_url = $options->pluginUrl .'/AjaxComments/';
$config  = $options->plugin('AjaxComments');
?>
<script type="text/javascript">
// <![CDATA[
// Ajax Comments v1.0.4 for Typecho Threaded Comments - by willin kan - URI: http://kan.willin.org/typecho/
var
   ld_img = '<img src="<?php echo $pic_url; ?>loading.gif" alt=""/> ',
   er_img = '<img src="<?php echo $pic_url; ?>no.png" alt=""/> ',
   ok_img = '<img src="<?php echo $pic_url; ?>yes.png" alt=""/> ',
   htm_1  = '<div id="loading">' + ld_img + '<\/div><div id="error">' + er_img + '<span id="msg"><\/span><\/div>',
   htm_2  = '<div class="success">' + ok_img + '提交成功.<\/div>',
   htm_3  = '<textarea name="ajaxComment" class="comm_area" cols="100%" rows="4"><\/textarea>',
   txt_1  = '必须填写用户名',
   txt_2  = '必须填写电子邮箱地址',
   txt_3  = '邮箱地址不合法',
   txt_4  = '必须填写评论内容',
   txt_5  = 'Spam Detected!';

//jQuery.noConflict();
//jQuery(document).ready(function($) {
function ajaxComments(){
   $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

var
   wait_time      = <?php echo $options->commentsPostIntervalEnable * $options->commentsPostInterval; ?>,
   comments_order = '<?php echo $options->commentsOrder; ?>',
   comment_list   = '<?php echo $config->_comment_list; ?>',
   comments       = '<?php echo $config->_comments; if (strpos($config->_comments, " ")) echo ":first"; ?>',
   comment_reply  = '<?php echo $config->_comment_reply; ?>',
   comment_form   = '<?php echo $config->_comment_form; ?>',
   respond        = '<?php echo $config->_respond; ?>',
   textarea       = '<?php echo $config->_textarea; ?>',
   submit_btn     = '<?php echo $config->_submit; ?>',

   new_id = '', parent_id = '';
   $(textarea).after(htm_1, htm_3);
   $('#loading, #error, .comm_area').hide();
   $(submit_btn).attr('disabled', false);
   $msg = $('#msg');

click_bind();

$(comment_form).submit(function() { // 提交
   $msg.empty();
   $('#error').hide();
   $(submit_btn).attr('disabled', true).fadeTo('slow', 0.5);

 /* 预检 */
 if($(comment_form).find('#author')[0]) {

   if($(comment_form).find('#author').val() == '') {
     $msg.text(txt_1);
     err_effect(); return false;
   }

   if($(comment_form).find('#mail').val() == '') {
     $msg.text(txt_2);
     err_effect(); return false;
   }

   var filter = /^[^@\s<&>]+@([a-z0-9]+\.)+[a-z]{2,4}$/i;
   if(!filter.test($(comment_form).find('#mail').val())) {
     $msg.text(txt_3);
     err_effect(); return false;
   }

   if($(comment_form).find('.comm_area').val() != '') {
     $msg.text(txt_5);
     $('#error').slideDown(); return false;
   }

 }

 if($(comment_form).find(textarea).val() == '') {
   $msg.text(txt_4);
   err_effect(); return false;
 }

 $('#loading').show();

$.ajax({
   url:  $(this).attr('action'),
   type: $(this).attr('method'),
   data: $(this).serializeArray(),

   success: function(data) {
    $('#loading').slideUp();

    try {
       if (!$(comment_list, data).length) {
           if (data.indexOf('Error') > -1) { // 返回 Error
               $msg.text($('p', data).text());
           } else {
               $msg.html(data); 
               if (data.indexOf('Spam') > -1) {$('#error').slideDown(); return false;}
           }
           err_effect(); return false;

       } else {

         new_id = $(comment_list, data).html().match(/id=\"?comment-\d+/g).join().match(/\d+/g).sort(function(a,b){return a-b}).pop(); // 找新 id

         data = $('#comment-' + new_id, data).hide(); // 取新评论

         $('#reply-to-' + new_id, data).before(htm_2);

         if (!$(comment_list).length) $(respond).before('<ol <?php echo strtr( $config->_comment_list, array( "." => "class=\"", "#" => "id=\"")). "\""; ?>><\/ol>'); // 加 ol

         parent_id
             ? (comments_order == 'DESC' && $('#' + parent_id + ' li').length ? $('#' + parent_id + ' li:first').before(data) : $(respond).before(data), parent_id = '') // 子层
             : $(comment_list+':first').append(data); // 底层

         $('#comment-' + new_id).fadeIn(); // 显示
         $(comments).length ? (n = parseInt($(comments).text().match(/\d+/)), $(comments).text($(comments).text().replace(n, n + 1))) : 0; // 评论数

         TypechoComment.cancelReply();
         $(textarea).attr('value', '');
         $(textarea).val('');
         $(comment_reply + ' a, #cancel-comment-reply-link').unbind('click'); click_bind(); // 新评论绑定
         $('#author').length ? countdown() : $(submit_btn).attr('disabled', false).fadeTo('slow', 1);
         $body.animate({scrollTop: $('#comment-' + new_id).offset().top - 200}, 500);

       }
    } catch (e) {
         alert('Error!\n\n' + e);
    }

   } // end success()

 }); // end ajax()


 return false; 

 }); // end $(comment_form).submit()

function click_bind() { // 绑定
  $(comment_reply + ' a').click(function() { // 回复
      $body.animate({scrollTop: $(respond).offset().top - 180}, 400);
      h = $(this)[0].href;
      parent_id = 'comment-' + h.substring(h.indexOf('replyTo=') + 8, h.indexOf('#'));
      $(textarea).focus();
  });
  $('#cancel-comment-reply-link').click(function() { // 取消
     parent_id = '';
  });	
}

function err_effect() { // 出错
  $('#error').slideDown();
  setTimeout(function() {$(submit_btn).attr('disabled', false).fadeTo('', 1); $('#error').slideUp();}, 3000);
}

var wait = wait_time, submit_val = $(comment_form).find(submit_btn).val();

function countdown() { // 计时
  wait > 0 ? ($(submit_btn).val(wait), wait--, setTimeout(countdown, 1000))
           : ($(submit_btn).val(submit_val).attr('disabled', false).fadeTo('slow', 1), wait = wait_time);
}
};
//}); // end jQ
// ]]>
</script>