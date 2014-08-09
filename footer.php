<?php if (empty($_GET['raw']) || $_GET['raw'] != 'true'): ?>
</div>
<div class="page-footer">
    <a class="footer-rss-wrap" href="<?php $this->options->feedUrl(); ?>" target="_blank" bw-static><span class="footer-rss icon-feed"></span></a>
    <div class="cont-wrap"><div class="grid_12">
    	<p>
(UTC+8)&nbsp;版权所有 &copy; 2010 - <?php echo date("Y");?> <a href="//shansing.com/about/">闪闪的星</a></p>
<p>自豪地采用 <a href="http://typecho.org" target="_blank">Typecho</a>，模板由 <a href="http://breeswish.org" target="_blank">Breezewish</a> 设计后 <a href="http://shansing.com" target="_blank">Shansing</a> 移植而来，驱动于<a href="http://billing.zzxx.in/aff.php?aff=164" target="_blank" rel="nofollow">通达主机</a></p>
    </div><div class="clear"></div></div>
</div></div>
<script type="text/javascript">
function externallinks() {
if (!document.getElementsByTagName){return};
var anchors = document.getElementsByTagName("a");
for (var i=0; i<anchors.length; i++) {
var anchor = anchors[i];
if (anchor.getAttribute("rel") == ("external nofollow")){
anchor.target = "_blank";}}}
//window.onload = externallinks;
</script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/jquery.min.js?v=140802001'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/main.js?v=140807002'); ?>"></script>
<!-- nuffnang -->
<script type="text/javascript">
if(document.location.protocol != "https:"){

nuffnang_bid = "66ef0168777def2db9f1bf346ae2c52f";
document.write( "<div id='nuffnang_bn'></div>" );
(function() {	
var nn = document.createElement('script'); nn.type = 'text/javascript';    
nn.src = 'http://synad2.nuffnang.com.cn/bn2.js';    
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(nn, s.nextSibling);
})();

}
</script>
<!-- nuffnang-->
<?php endif;  ?><div id="footer-js">
<script type="text/javascript" src="//tajs.qq.com/stats?sId=30178080" charset="UTF-8"></script>
<?php $this->footer(); ?>
</div><?php if (empty($_GET['raw']) || $_GET['raw'] != 'true'):  ?>
</body>
</html>
<?php endif; ?>