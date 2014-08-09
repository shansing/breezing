<?php $this->need('header.php'); ?>
<script>window.disableDynamicLoading = true;</script>
<div class="error-404-wrap">
    <p>啊~哦~ 你要查看的页面不存在或已删除！</p>
	<p>请检查您输入的网址是否正确，或者在<a href="//shansing.com/about/">留言板</a>上寻求帮助。</p>
	<p><br />再悄悄告诉你，使用搜索会非常棒哦！</p>
	       <form id="searchform" method="post" action="./">
       <input type="text" name="s" id="s" size="20" placeholder="输入关键词..." autocomplete="off"/>
        <button type="submit">搜索</button>
        </form>
	<p><br />若你想在其他页面使用站内搜索，请随时滑到侧边栏哦！</p>
</div>
<div class="clear"></div>
</div>
<?php $this->need('footer.php'); ?>
