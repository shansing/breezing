<?php
/**
 * 穿越
 *
 * @package custom
 */
 $this->need('header.php'); ?>
<div class="grid_9">
<div class="post">
    <div class="post-detail-body">
        <h3>闪星碎语</h3>
            <?php $this->comments()->to($comments); ?>
            <?php if ($comments->have()): ?> 
            <?php $comments->pageNav(); ?>
            <style type="text/css">.ttime{color:#ccc}</style>
            <?php //$comments->listComments(); 
$slug = "cross";    //页面缩略名
$limit = 35;    //调用数量
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
     //   echo '<li>';
     //   echo '<a href="' . $page['permalink'] . "#comment-" . $comment['coid'] . '" title="' . $comment['text'] . '">';
     //   echo Typecho_Common::subStr(strip_tags($comment['text']), 0, $length, '...') . '</a>';
     //   echo '</li>';
     echo '<p><span class="ttext">'.$comment['text'].'</span> <span class="ttime">'.date('Y-n-j H:i:s',$comment['created']+($this->options->timezone - idate("Z")))."</span></p>\n";
    }
} else {
    echo "<li>No Comments</li>";
}?>
            <?php endif; ?>

            <?php if($this->user->hasLogin()): ?>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment_form">
                <p><input id="comment" type="text" name="text" size="70"><?php $this->remember('text'); ?></input><input type="submit" value="发表碎语" class="submit" id="submit" /></p>
            </form>
            <script type="text/javascript">
document.getElementById("comment").onkeydown = function (moz_ev)
{var ev = null;
if (window.event){ev = window.event;}else{ev = moz_ev;}
if (ev != null && ev.ctrlKey && ev.keyCode == 13)
{document.getElementById("submit").click();}}
</script>
            <?php endif; ?>
        <?php $this->content();$this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->parse('<li>{year}-{month}-{day} : <a href="{permalink}">{title}</a></li>'); ?>
        
            文章分类：<?php /*<br />
            <ul><?php $this->widget('Widget_Metas_Category_List')
                ->parse('<li><a href="{permalink}">{name}</a> ({count})</li>'); ?>
            </ul>*/?>就在顶部呢。<br />
            文章标签：<br />
            <?php Typecho_Widget::widget('Widget_Metas_Tag_Cloud')->to($tags); ?>
             <?php while ($tags->next()): ?>
    <a style="color:rgb(<?php echo(rand(0,255)); ?>,<?php echo(rand(0,255)); ?>,
           <?php echo(rand(0,255)); ?>)" href="<?php $tags->permalink();?>">
         <?php $tags->name(); ?></a>
    <?php endwhile; ?>
    <br /><em>（其他类型的站点地图：<a href="http://shansing.com/sitemap/">XML 网站地图</a> | <a href="http://shansing.com/feed/">RSS 源</a>）</em>
    </div>
</div>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>