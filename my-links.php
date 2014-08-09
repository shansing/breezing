<?php
/**
 * 链接
 *
 * @package custom
 */
 $this->need('header.php'); ?>
<div class="grid_9">
<div class="post">
    <div class="post-detail-body">
        <p>总是在同一个博客转悠也不好，多去其他博客探索吧！</p>
			<style type="text/css">.page-links{overflow:hidden;margin:0 0 24px;padding:0;}
.page-links h4{border-bottom:1px solid #bfbebe;text-align:center;margin:0;}
.page-links ul{margin:0;padding:5px 0 0 0;}
.page-links ul li{float:left;width:160px;line-height:16px;height:16px;margin:5px 5px 0;padding:0;list-style-type:none;}
.page-links ul li:hover{background:#f2f2f2;}
.page-links ul li img{width:16px;height:16px;margin:0 5px -2px 0;padding:0;border:none;}</style>
<h3>友情链接：</h3>
<div class="page-links"><h4>内页链接</h4>
<ul><?php $mypattern = '<li><!-- img src="//www.google.com/s2/favicons?domain_url={url}" onerror="javascript:this.src=\'//s2.googleusercontent.com/s2/favicons?domain_url={url}\'" / --><a href="{url}" title="{title}" target="_blank" >{name}</a></li>'."\n"; Links_Plugin::output($mypattern, 0, "one");?></ul></div>
<div class="page-links"><h4>建议网站</h4>
<ul><?php Links_Plugin::output($mypattern, 0, "good");?></ul></div>
<div class="page-links"><h4>首页链接</h4>
<ul><?php Links_Plugin::output($mypattern, 0, "five");?></ul></div>
<div class="page-links"><h4>全站链接</h4>
<ul><?php Links_Plugin::output($mypattern, 0, "ten");?></ul></div>
    		    <?php $this->content(); ?>
    </div>
</div>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>