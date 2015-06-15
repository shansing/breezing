<?php
/**
 * Breezewish 的博客使用的主题（BWBlog 平台），由 Shansing(闪闪的星) 移植到 Typecho 并有所改进。
 * 
 * @package Breezing for Typecho
 * @author alter by Shansing
 * @version 0.11
 * @link http://shansing.com
 */
 
 $this->need('header.php');
 ?>
<div class="grid_9">

<?php if ($this->have()): ?>

<div class="post-list">
    <?php while($this->next()): ?>
<div class="post">
    <div class="post-title"><h2><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h2></div>
    <div class="post-body"><?php $this->content('阅读全文 &raquo;'); ?>
</div>
    <div class="post-tags">
        <div class="post-catalog-item post-catalog-item-<?php echo $this->category; ?>"><?php $this->category(','); ?></div>
        <div class="post-tag-item"><?php $this->tags('</div> <div class="post-tag-item">', true, '无标签'); ?></div>
        <div class="clear"></div>
    </div>
    <div class="post-time">Published @ <time class="time" datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y.m.d H:i'); ?></time></div>
</div>
    <?php endwhile; ?>
</div>
<div class="post-nav">
<?php $this->pageNav('&lt;', '&gt;', 6, '...'); ?>
<div class="clear"></div>
</div>

<?php else:; ?>
<div class="post">
    <div class="post-title"><h2>没有找到你想要的内容</h2></div>
    <div class="post-body">
    <p>抱歉。</p>
    <p>如果你正在使用<strong>搜索</strong>功能，建议：</p>
    <ul><li>检查输入是否正确；</li><li>简化输入词；</li><li>尝试其他相关词，如同义、近义词等。</li></ul>
    <p>如果您正在浏览某个<strong>分类</strong>或<strong>标签</strong>的文章，出现这个页面则是因为没有与这个分类或标签所对应的文章。建议：</p>
    <ul><li>返回浏览其他分类或标签存档；</li><li>到<a href="//shansing.com/cross/">时光机</a>寻找感兴趣的文章；</li><li>使用搜索功能代替存档浏览。</li></ul>
	<p>如果你的问题仍然得不到解决，可以到<a href="//shansing.com/about/">留言板</a>寻求帮助。</p>	
</div>
</div>
<?php endif; ?>

</div>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
