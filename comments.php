<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
    <h4><?php $this->commentsNum('居然没有评论', '只有一条评论啊', '%d 条评论'); ?></h4>
    
    <?php $comments->listComments(); ?>

    <?php if (($this->options->commentsPageBreak)): ?>
    <nav class="navigation">
        <?php $comments->pageNav('&lt;', '&gt;'); ?>
    </nav>
    <div class="clear"></div>
    <?php endif; ?>
    <?php endif; ?>

    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
    
        <h4 id="response">发表评论&raquo; <small><?php $comments->cancelReply(); ?></small></h4>
        <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form">
            <?php if($this->user->hasLogin()): ?>
            <p>欢迎 <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a> 归来！ <a href="<?php $this->options->logoutUrl(); ?>" title="Logout">退出&raquo;</a></p>
            <?php else: ?>
            <p class="comment-form-author">
                <label for="author" class="required"><i class="ico-user"></i></label>
                <input type="text" name="author" id="author" class="text" placeholder="称呼 (必填)" value="<?php $this->remember('author'); ?>" />
            </p>
            <p class="comment-form-email">
                <label for="email"<?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>><i class="ico-mail"></i></label>
                <input type="email" name="mail" id="mail" class="text" placeholder="邮箱 (必填,将保密)" value="<?php $this->remember('mail'); ?>" />
            </p>
            <p class="comment-form-url">
                <label for="url"<?php if ($this->options->commentsRequireURL): ?> class="required"<?php endif; ?>><i class="ico-globe"></i></label>
                <input type="url" name="url" id="url" class="text" placeholder="网址 (选填)" value="<?php $this->remember('url'); ?>" />
            </p><p class="nospam"><strong>NO SPAMS!</strong> <em>不要发垃圾评论哦！</em></p>
            <?php endif; ?>
            <p class="comment-form-comment">
            <label id="lblbanmail"><input type="checkbox" name="banmail" id="banmail" value="stop"<?php if((array_key_exists('HTTP_DNT', $_SERVER)) && $_SERVER['HTTP_DNT'] == 1){echo ' checked="checked"';} ?> />拒收此次评论回复邮件通知</label>
                <!-- <label for="textarea" class="required">内容</label> -->
                <textarea id="comment" rows="8" cols="45" name="text" class="textarea"><?php $this->remember('text'); ?></textarea>
            </p>
            <p class="submitp">
                <input id="submit" type="submit" name="submit" class="submit" value="打完收工 (Ctrl+Enter)" /><?php Smilies_Plugin::output(); ?>
            </p>
        </form>
            <script type="text/javascript">
document.getElementById("comment").onkeydown = function (moz_ev)
{var ev = null;
if (window.event){ev = window.event;}else{ev = moz_ev;}
if (ev != null && ev.ctrlKey && ev.keyCode == 13)
{document.getElementById("submit").click();}}
</script>
    </div>
    <?php else: ?>
    <h4>此处评论已关闭，客欲<a href="//shansing.com/guestbook/">留言请移步</a>。</h4>
    <?php endif; ?>
</div>
