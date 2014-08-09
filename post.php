<?php $this->need('header.php'); ?>
<div class="grid_9">
<div class="post">
    <div class="post-detail-body">
        <?php $this->content(); ?>
        <div id="copypost">若无特别说明，本文系原创，遵循 <a href="http://creativecommons.org/licenses/by-nc/3.0/deed.zh" target="_blank">署名-非商业性使用 3.0 (CC BY-NC 3.0)</a> 协议，转载文章请注明来自【<a href="http://shansing.com">闪星空间</a>】，或链接上原文地址：<a href="<?php echo strtr($this->permalink,array('https://' => 'http://'));; ?>"><?php echo strtr($this->permalink,array('https://' => 'http://'));; ?></a></div>
    </div>
</div>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
