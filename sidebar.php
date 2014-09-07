<div class="grid_3">
<div class="sidebar">

<div class="sidebar-module sidebar-about">
    <div class="sidebar-body">
       <form id="searchform" method="post" action="./">
       <input type="text" name="s" id="s" size="15" placeholder="站内搜索关键词..." autocomplete="off" />
        <button type="submit">搜索</button>
        </form>
        <a href="" title="<?php $this->options->siteUrl(); ?>" id="searchurl"></a>
    </div>
</div>

<?php if($this->is('post')):?>
<div class="sidebar-module sidebar-postinfo">
    <h4>文章信息</h4>
    <div class="sidebar-body">
        <div class="sidebar-post-time sidebar-item"><span class="icon-time icon"></span> <time class="time" datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y.m.d H:i'); ?></time></div>
        <div class="sidebar-post-tags sidebar-item"><span class="icon-tag icon"></span>
        <div class="sidebar-post-tag-item"><?php $this->tags('</div> <div class="sidebar-post-tag-item">', true, '无标签'); ?></div>
                <div class="clear"></div></div>
        <div class="sidebar-post-category sidebar-item"><span class="icon-category icon"></span>
        <div class="sidebar-post-catalog-item sidebar-post-catalog-item-<?php echo $this->category; ?>"><?php $this->category(','); ?></div>
        <div class="clear"></div></div>
    </div>
</div>
<?php endif;?>

<?php if($this->is('index')) { ?>
<div class="sidebar-module sidebar-about">
    <h4>站长碎语</h4>
    <div class="sidebar-body">
    <p><?php

 //$comments->listComments(); 
$slug = "cross";    //页面缩略名
$limit = 1;    //调用数量
$length = 140;    //截取长度
$ispage = true;    //true 输出slug页面评论，false输出其它所有评论
$isGuestbook = $ispage ? " = " : " <> ";
 
$db = $this->db;    //Typecho_Db::get();
$options = $this->options;    //Typecho_Widget::widget('Widget_Options');
 
$page = $db->fetchRow($db->select()->from('table.contents')
    ->where('table.contents.status = ?', 'publish')
    ->where('table.contents.created < ?', $options->gmtTime)
    ->where('table.contents.slug = ?', $slug));
 
if ($page) {
    $type = $page['type'];
    $routeExists = (NULL != Typecho_Router::get($type));
    $page['pathinfo'] = $routeExists ? Typecho_Router::url($type, $page) : '#';
    $page['permalink'] = Typecho_Common::url($page['pathinfo'], $options->index);
 
    $comments = $db->fetchAll($db->select()->from('table.comments')
        ->where('table.comments.status = ?', 'approved')
        ->where('table.comments.created < ?', $options->gmtTime)
        ->where('table.comments.type = ?', 'comment')
        ->where('table.comments.cid ' . $isGuestbook . ' ?', $page['cid'])
        ->order('table.comments.created', Typecho_Db::SORT_DESC)
        ->limit($limit));
 
    foreach ($comments AS $comment) {
     echo $comment['text'].' <a href="//shansing.com/cross/"> <span>'.date('Y-n-j H:i:s',$comment['created']+($this->options->timezone - idate("Z"))).'</span></a>';
    }
} else {
    echo "<li>No Comments</li>";
}?></p>
    </div>
</div>
<?php } ?>

<div class="sidebar-module sidebar-catalogs">
    <h4>博客页面</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
       <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
        <li class="sidebar-item"><a href="http://xuwei.de" target="_blank" title="跳转到 XuWei.De">关于我</a></li>
        <?php while($pages->next()): ?>
         <li class="sidebar-item"><a href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
        <?php endwhile; ?>
    </ul></div>
</div>


<div class="sidebar-module sidebar-catalogs">
<?php if ($this->is('post')) {$this->related(3)->to($relatedPosts); ?>
    <h4>相关文章</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
<?php while ($relatedPosts->next()): ?>
    	<li class="sidebar-item"><a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>"><?php $relatedPosts->title(); ?></a></li>
    <?php endwhile; }else{ ?>
    <h4>随机文章</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
       <?php RandomArticleList::parse('<li class="sidebar-item"><a href="{permalink}" title="{title}">{title}</a></li>'); ?>
        <?php } ?>
    </ul></div>
</div>

<div class="sidebar-module sidebar-catalogs">
<?php if(CommentTracks::output()->to($tracks)->have() && !$this->is('index')): ?>
    <h4>您的足迹</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
<?php $cishu = 0; while($tracks->next()): $cishu = $cishu + 1;if ($cishu <= 3) {?>
		<li class="sidebar-item"><a href="<?php $tracks->permalink(); ?>" title="<?php $tracks->title(); ?>"><?php $tracks->excerpt(20, '...'); ?></a></li>
<?php } endwhile; ?><?php else: ?>
    <h4>最新评论</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
       					<?php $this->widget('Widget_Comments_Recent','ignoreAuthor=true')->to($comments); ?>
	<?php while($comments->next()): ?>
		<li class="sidebar-item"><a href="<?php $comments->permalink(); ?>"><?php $comments->author(false); ?></a>: <?php $comments->excerpt(20, '...'); ?></li>
	<?php endwhile; ?><?php endif; ?>
    </ul></div>
</div>

<div class="sidebar-module sidebar-catalogs">
    <h4>左邻右舍</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
       <?php $mypattern1 = "<li class=\"sidebar-item\"><a href=\"{url}\" title=\"{title}\" target=\"_blank\">{name}</a></li>\n";
				if ($this->is('index')) : Links_Plugin::output($mypattern1, 0, "five");  endif; Links_Plugin::output($mypattern1, 0, "ten");?>
				<?php /*if (!$this->is('index')): ?>
					<ul>
						<?php Typecho_Widget::widget('Widget_Stat')->to($stat);?>
<?php _e('<li>评论:%s条</li>',$stat->publishedCommentsNum ); echo "<li>运行:".floor((time() - strtotime("2010-3-23")) / 86400)."天</li>"; ?>
					</ul>
				<?php endif;*/ ?>
    </ul></div>
</div>

<?php if($this->is('index')) { ?>
<div class="sidebar-module sidebar-catalogs">
    <h4>特别链接</h4>
    <div class="sidebar-body"><ul class="sidebar-list">
       <li class="sidebar-item"><a href="//x.shansing.com" target="_blank" title="一个在线解密游戏">闪星未名角</a></li>
       <li class="sidebar-item"><a href="http://api.shansing.com" target="_blank" title="一些API">闪星开发者接口</a></li>
        <li class="sidebar-item"><a href="//shansing.net" target="_blank" title="The English Site">ENGLISH</a></li>
    </ul></div>
</div>
<?php } ?>

</div>
</div><div class="clear"></div>
</div>