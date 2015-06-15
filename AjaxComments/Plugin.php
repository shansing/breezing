<?php
/**
 * Typecho 内置嵌套评论专用 Ajax Comments
 * 
 * @package AjaxComments 
 * @author willin kan
 * @version 1.0.5
 * @update: 2011.06.07
 * @link http://kan.willin.org/typecho/
 */
class AjaxComments_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive') ->beforeRender  = array('AjaxComments_Plugin', 'autoField');
        Typecho_Plugin::factory('Widget_Archive') ->header        = array('AjaxComments_Plugin', 'headerScript');
        Typecho_Plugin::factory('Widget_Archive') ->footer        = array('AjaxComments_Plugin', 'footerScript');
        Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('AjaxComments_Plugin', 'finishComment');

        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();

        // comments 表中若无 notify 字段则添加
        if (!array_key_exists('notify', $db->fetchRow($db->select()->from('table.comments'))))
            $db->query('ALTER TABLE `'. $prefix .'comments` ADD `notify` tinyint(1) DEFAULT 1;');

        return '此插件必须正确设置才能正常使用.';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $db = Typecho_Db::get();
        $options = Typecho_Widget::widget('Widget_Options');
        $file_contents = '';
        $function_not_exists = '';
        $hit_exists = '';

        $finishComment = $options->plugins['handles']['Widget_Feedback:finishComment'];
        foreach ($finishComment as $array) $finishComment_plugins[] = $array[0];
        $finishComment_plugins_flip = array_flip($finishComment_plugins);
        if (in_array('AjaxComments_Plugin', $finishComment_plugins) && $finishComment_plugins_flip['AjaxComments_Plugin'] < count($finishComment_plugins)-1)
            echo '<div class="container message notice typecho-radius-topleft typecho-radius-topright typecho-radius-bottomleft typecho-radius-bottomright" style="width:97%;margin:0 0 20px">检测到 AjaxComments 插件未在最后启用, 请重新激活 AjaxComments 插件, 才能让 '.$finishComment_plugins[$finishComment_plugins_flip['AjaxComments_Plugin']+1].' 优先执行.</div><div style="clear:both"></div>';

        // 图片
        $img_url = $options->pluginUrl .'/AjaxComments/';
        $style  = "alt='' style='vertical-align:middle'";
        $wn_img = "<img src='{$img_url}warning.png' {$style}/>";
        $er_img = "<img src='{$img_url}no.png' {$style}/>";
        $ok_img = "<img src='{$img_url}yes.png' {$style}/>";

        // 获取评论
        $comment = $db->fetchRow(Typecho_Widget::widget('Widget_Abstract_Comments')->select('cid')->from('table.comments')
                   ->where('type = ? AND status = ?', 'comment', 'approved')
                   );

        if ($comment) {
            $contents = Typecho_Widget::widget('Widget_Abstract_Contents');

            // 获取评论所在文章
            $post = $db->fetchRow($contents->select()->where('table.contents.cid = ?', $comment['cid']));

            // 获取文章的 permalink
            $result = $contents->push($post);
            $permalink = $result['permalink'];

            // 用 file_get_contents() 读取网页结构
            if (function_exists('file_get_contents')) {
                $file_contents = file_get_contents($permalink);

            // 用 cURL 读取网页结构
            } elseif (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $permalink);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
                  ob_start();
                  curl_exec($ch);
                  curl_close($ch);
                  $file_contents = ob_get_contents();
                  ob_end_clean();
                  
            } else $function_not_exists = '您的服务器没 file_get_contents(), 也没 cURL 功能, ';
        }

        // 是否已登记
        $registered = $db->fetchRow($db->select()->from('table.options')
                      ->where('name = ?', 'plugin:AjaxComments')
                      );

        // jQuery 来源
        $jq_set = new Typecho_Widget_Helper_Form_Element_Radio(
        'jq_set', array('0'=> '自己处理', '1'=> '随着本插件载入'), 0, 'jQuery 来源',
        '若选择 "随着本插件载入", 会从 Google API 自动载入 jQurey 1.2.6 到 header().');
        $form->addInput($jq_set);

        // 评论总数 #comments h4
        $trg = $registered ? $options->plugin('AjaxComments')->_comments : '#comments h4';
        $trg_array = explode(' ', $trg);
        $htm_trg = strtr($trg_array[0], array('.' => 'class="', '#' => 'id="'));
        $htm_trg .= (stristr($htm_trg, 'id=') || stristr($htm_trg, 'class=')) ? '"' : '';
        if (!isset($trg_array[1])) {
            $trg_array[1] = '';
            $chkd = stripos($file_contents, $htm_trg) ? $ok_img : $wn_img;
        } else {
            $tmp = substr($file_contents, stripos($file_contents, $htm_trg), 80);
            $chkd = (stripos($file_contents, $htm_trg) && stristr($tmp, $trg_array[1])) ? $ok_img : $wn_img;
            $trg_array[1] = '&lt;'. $trg_array[1]. '>';
        }
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_comments = new Typecho_Widget_Helper_Form_Element_Text(
        '_comments', NULL, $trg, '评论总数', '　'. $chkd. '　&lt;'. $htm_trg. '>'. $trg_array[1]. '有 xx 条评论...');
        $_comments->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_comments);

        // 评论主体 .comment-list
        $trg = $registered ? $options->plugin('AjaxComments')->_comment_list : '.comment-list';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_comment_list = new Typecho_Widget_Helper_Form_Element_Text(
        '_comment_list', NULL, $trg, '评论主体', '　'. $chkd. '　&lt;ol '. $htm_trg. '">');
        $_comment_list->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_comment_list);

        // 回复 .comment-reply
        $trg = $registered ? $options->plugin('AjaxComments')->_comment_reply : '.comment-reply';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_comment_reply = new Typecho_Widget_Helper_Form_Element_Text(
        '_comment_reply', NULL, $trg, '回复', '　'. $chkd. '　&lt;div '. $htm_trg. '">&lt;a href=" ...');
        $_comment_reply->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_comment_reply);

        // 表单 #comment_form
        $trg = $registered ? $options->plugin('AjaxComments')->_comment_form : '#comment_form';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $htm_trg .= stristr($htm_trg, 'id=') ? '"' : '';
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_comment_form = new Typecho_Widget_Helper_Form_Element_Text(
        '_comment_form', NULL, $trg, '表单', '　'. $chkd. '　&lt;form .. '. $htm_trg. ' ... >');
        $_comment_form->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_comment_form);

        // 评论框 .respond
        $trg = $registered ? $options->plugin('AjaxComments')->_respond : '.respond';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $htm_trg .= stristr($htm_trg, 'id=') ? '"' : '';
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_respond = new Typecho_Widget_Helper_Form_Element_Text(
        '_respond', NULL, $trg, '评论框', '　'. $chkd. '　&lt;div id="respond-post- xx " '. $htm_trg. ' ... >');
        $_respond->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_respond);

        // 內容 .textarea
        $trg = $registered ? $options->plugin('AjaxComments')->_textarea : '.textarea';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $htm_trg .= stristr($htm_trg, 'id=') ? '"' : '';
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_textarea = new Typecho_Widget_Helper_Form_Element_Text(
        '_textarea', NULL, $trg, '內容', '　'. $chkd. '　&lt;textarea .. '. $htm_trg. ' ... >');
        $_textarea->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_textarea);

        // 提交 .submit
        $trg = $registered ? $options->plugin('AjaxComments')->_submit : '.submit';
        $htm_trg = strtr($trg, array('.' => 'class="', '#' => 'id="'));
        $htm_trg .= stristr($htm_trg, 'id=') ? '"' : '';
        $chkd = (stripos($file_contents, $htm_trg)) ? $ok_img : $wn_img;
        if (!stristr($htm_trg, 'id=') && !stristr($htm_trg, 'class=')) $chkd = $er_img;
        $_submit = new Typecho_Widget_Helper_Form_Element_Text(
        '_submit', NULL, $trg, '提交', '　'. $chkd. '　&lt;input .. '. $htm_trg. ' ... ><br/><br/>
        1. 提示不正常项目请查找模板比对, 修改后先保存设置, 再回来重新检查.<br/>
        2. id 使用 ( # ) ;　class 使用 (<strong> . </strong>) ;　请勿直接输入 "id" 或 "class".<br/>
        ( 测试结果僅供参考, 若还有不正常, 请详细修改以上对应标签. )<br/>
        有问题欢迎到 <a href="http://kan.willin.org/typecho/">http://kan.willin.org/typecho/</a> 共同讨论.
        ');
        $_submit->input->setAttribute('style', 'float:left; width:200px;');
        $form->addInput($_submit);

        // 评论回复邮件通知
        $mail_enable = new Typecho_Widget_Helper_Form_Element_Radio(
        'mail_enable', array('0'=> '不使用', '1'=> '注冊用户不收邮件, 其它勾选通知的被回复者才收邮件<br/>',
        '2'=> '勾选通知的被回复者 (含注冊用户) 都收邮件', '3'=> '所有被回复者都收邮件'), 2,
        '评论回复邮件通知', '
        1. 若要更改邮件排版, 请在 /AjaxComments/message-layout.php 的 "邮件排版" 自行修改.<br/>
        2. 本邮件支援 <a href="http://kan.willin.org/typecho/smilies-plugin.html">表情及贴图插件</a>.');
        $form->addInput($mail_enable);

        // 邮件通知勾选栏
        $field_enable = new Typecho_Widget_Helper_Form_Element_Radio(
        'field_enable', array('0'=> '不使用或手工添加', '1'=> '自动添加在 "提交" 前面', '2'=> '自动添加在 "提交" 后面'), 2, '邮件通知勾选栏',
        '在表单之內手工添加 &lt;?php AjaxComments_Plugin::notifyField(); ?> 也可出现勾选栏.');
        $form->addInput($field_enable);

        // 测试结果
        if ($file_contents) {
            $msg = (stripos($file_contents, 'id="cancel-comment-reply-link"'))
                   ? '已采用内置嵌套评论 ' . $ok_img
                   : $er_img . '<span style="color:#f00;"> 不支持内置嵌套评论, 不能使用本插件, 强行使用将出现不可预料的结果.</span>';
        } else $msg = $wn_img . '<span style="color:#f00;"> 警告! ' . $function_not_exists . '未能获取任何评论, 以下测试失败...</span>
                   <br/><span style="padding-left:170px;color:#080">尽管如此, 您还是可在前台自行测试, 只是比较辛苦.</span>';

        if (!$options->commentsThreaded)
            $msg = $wn_img . '<span style="color:#d60;"> 评论回复功能未启用, 请先在 "设置"->"评论"->"评论显示" 启用评论回复.</span>';

        echo '<span style="font-size:15px;">当前使用的外观是 [ <strong>', $options->theme, '</strong> ] ', $msg, '</span><hr/>';
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 加入 header
     * 
     * @access public
     * @return void
     */
    public static function headerScript()
    {
        // 载入 jQuery
        if (Typecho_Widget::widget('Widget_Options')->plugin('AjaxComments')->jq_set)
            echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js'></script>\n";
    }

    /**
     * 加入 footer
     * 
     * @access public
     * @return void
     */
    public static function footerScript()
    {
        /** 载入 AjaxComments 的 js **/
        if (Typecho_Widget::widget('Widget_Archive')->is('single'))
            include('AjaxComments/typecho-ajax-comm.php');
    }

    /**
     * 评论后续处理
     * 
     * @access public
     * @param object $comment 评论信息
     * @return void
     */
    public static function finishComment($comment)
    {
        $db = Typecho_Db::get();
        $options = Typecho_Widget::widget('Widget_Options');
        $mail_enable = $options->plugin('AjaxComments')->mail_enable;

        // 若 (未勾选邮件通知 && 不是后台回复)
        if (!Typecho_Request::getInstance()->comment_mail_notify && !strpos($_SERVER['HTTP_REFERER'], '/admin/'))
            $db->query($db->update('table.comments')->rows(array('notify' => 0))->where('coid = ?', $comment->coid));

        // 若 (启用邮件通知 && 有父层)
        if ($mail_enable && $comment->parent) {

            // 收件者
            $parent = $db->fetchRow($db->select('author', 'mail', 'text', 'notify')->from('table.comments')->where('coid = ?', $comment->parent));
            $to = $parent['mail'];

            // 所有注冊用户的 mail
            $users_mail_array = $db->fetchAll($db->select('mail')->from('table.users'));

            // 若 (收件者有邮箱 && ((收件者勾选邮件通知 && ((注冊用户不收邮件 && 不是寄给注册用户) || 勾选通知的被回复者才收邮件)) || 所有被回复者都收邮件))
            if ($to && (($parent['notify'] && (($mail_enable == 1 && !in_array($to, $users_mail_array[0])) || $mail_enable == 2)) || $mail_enable == 3)) {

                /** 邮件內容 **/
                include('AjaxComments/mail-layout.php');

                // 让邮件支援表情及贴图插件: http://kan.willin.org/typecho/smilies-plugin.html
                $message = Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx($message);

                /** 发送邮件 **/
                include('AjaxComments/send-mail.php');

            }
        }

    ################
    #   bug-fix    #
    ################
        // $comment->response->goBack('#' . $comment->theId); // 底层若有新评论超过翻页数, 评论有些已翻页, 会找不到被翻页的评论。 DESC 和 ASC 皆有问题, 此句只适用于不翻页情况.
        // $comment->response->redirect($comment->permalink); // 底层若有新评论超过翻页数, 在 DESC 下, 子层评论会全部翻页, 连不该翻页的也翻了. 在 DESC 会有问题, 此句只适用于 ASC.
        // 试了 var/Widget/Abstract/Comments.php ___permalink() 源文件的方法，错误如同上句.

        // 以下是我改写的:

            $coid = $comment->coid;
            $parent = $comment->parent;

            while ($parent) {
                $parentRows = $db->fetchRow($db->select('parent')->from('table.comments')
                              ->where('coid = ? AND status = ?', $parent, 'approved')
                              ->limit(1));
                $coid = $parent;
                $parent = $parentRows['parent'];
            }

            $select = $db->select('coid', 'parent')->from('table.comments')
                      ->where('cid = ? AND status = ?', $comment->parentContent['cid'], 'approved')
                      ->where('coid ' . ('DESC' == $options->commentsOrder ? '>=' : '<=') . ' ?', $coid)
                      ->order('coid', Typecho_Db::SORT_ASC);

            if ($options->commentsShowCommentOnly) {
                $select->where('type = ?', 'comment');
            }

            $comms = $db->fetchAll($select);

            $total = 0;

            foreach ($comms as $comm) {
                if (!$comm['parent']) { // 主要修改了这句
                    $total ++;
                }
            }

        $currentPage = ceil($total / $options->commentsPageSize);
        $pageRow = array('permalink' => $comment->parentContent['pathinfo'], 'commentPage' => $currentPage);
        $permalink = Typecho_Router::url('comment_page', $pageRow, $options->index);

        $comment->response->redirect($permalink); // 正确导向评论所在页
        /* 注意: 这边已重导向, 如果后面还有 plugins 也用了 finishComment 的接口就不执行了, 所以本插件必须在最后启用, 让其它 plugins 先执行. */
    }

    /**
     * 自动添加邮件通知勾选栏
     *
     * @access public
     * @return void
     */
    public static function autoField()
    {
        if (Typecho_Widget::widget('Widget_Archive')->is('single') && Typecho_Widget::widget('Widget_Options')->plugin('AjaxComments')->field_enable) {
            function field($input) {
                $field = AjaxComments_Plugin::getNotifyField();
                $field_enable = Typecho_Widget::widget('Widget_Options')->plugin('AjaxComments')->field_enable;
                if ($field_enable == 1) $before = $field;
                else $after = $field;
                return preg_replace("#<textarea(.+\n.*)<input(.+[\"\'])submit([\"\'].+)/>#", "<textarea$1$before<input$2submit$3/>$after", $input);
            }
            ob_start('field');
        }
    }

    /**
     * 输出邮件通知勾选栏
     *
     * @access public
     * @return string
     */
    public static function getNotifyField()
    {
        return '<label for="comment_mail_notify" style="display:inline;margin:0 20px"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" /> 有人回覆时邮件通知我　</label>';
    }

    public static function notifyField()
    {
        echo self::getNotifyField();
    }

}
