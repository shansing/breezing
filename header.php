<?php if (empty($_GET['raw']) || $_GET['raw'] != 'true'): ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php endif; ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no, target-densitydpi=device-dpi">
    <title><?php $this->archiveTitle(' &raquo; ', '', ' - '); ?><?php $this->options->title(); ?><?php if ($this->is('index')) : ?> - <?php if(CommentTracks::output()->to($tracks)->have()){echo $tracks->author(false) . " 欢迎归来";}else{echo "我寻找着更多闪闪的星";} ?><?php endif; ?></title>
    <script type="text/javascript">
    if(top.location!==self.location){top.location=self.location}else{if(top!==self){if(confirm("Reload?")){top.location.reload()}}};
    </script>
    <script type="text/javascript">
    var CONFIG = {"Prefix":"","URI":"<?php echo strtr($_SERVER['REQUEST_URI'], array($this->options->siteUrl => '','/' => '\/')); ?>"};
    </script>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css?v=140808001'); ?>" type="text/css" charset="UTF-8" />
    <link rel="shortcut icon" href="<?php $this->options->siteUrl('favicon.ico'); ?>">
    <?php $this->header();  ?>
    <meta name="author" content="闪闪的星" />
</head>
<?php if (empty($_GET['raw']) || $_GET['raw'] != 'true'): ?>
<body class="body-<?php if($this->is('post') || $this->is('category')){echo $this->category;}else{echo 'home';} ?>">
<div id="container">
<div class="page-header">
    <div class="page-header-bar">
    <div class="cont-wrap"><div class="grid_12">
        <a class="page-header-li page-header-li-home<?php if($this->is('index')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">主页</div><div class="page-header-li-d">HOME</div></a>
        <a class="page-header-li page-header-li-tech<?php if($this->is('category', 'tech')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>category/tech/"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">设计开发</div><div class="page-header-li-d">TECH</div></a>
        <a class="page-header-li page-header-li-share<?php if($this->is('category', 'share')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>category/share/"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">资源技巧</div><div class="page-header-li-d">SHARE</div></a>
        <a class="page-header-li page-header-li-news<?php if($this->is('category', 'news')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>category/news/"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">网络动态</div><div class="page-header-li-d">NEWS</div></a>
        <a class="page-header-li page-header-li-life<?php if($this->is('category', 'life')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>category/life/"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">生活杂烩</div><div class="page-header-li-d">LIFE</div></a>
        <a class="page-header-li page-header-li-diary<?php if($this->is('category', 'diary')): ?> page-header-li-active<?php endif; ?>" href="<?php $this->options->siteUrl(); ?>category/diary/"><div class="page-header-li-s"><div class="page-header-li-si"></div></div><div class="page-header-li-t">点滴记录</div><div class="page-header-li-d">DIARY</div></a>
        <div class="clear"></div>
    </div><div class="clear"></div></div>
    </div>
</div><div class="page-before"><div class="page-before-inner">
    <div class="cont-wrap"><div class="grid_12">
    <div class="page-title"><div class="page-title-content"><h1><?php if($this->is('index')){$this->options->title();}elseif ($this->is('post')){$this->archiveTitle(' &raquo; ', '', '');}else{$this->options->title(); echo ' &raquo '; $this->archiveTitle(' &raquo; ', '', '');} ?></h1>
</div></div>
    </div><div class="clear"></div></div>
</div></div>
<div class="page-before-push"></div>
<div class="page-content">
<?php else: ?>
<div>
    <div id="raw_info">
        <div class="role-title"><?php $this->archiveTitle(' &raquo; ', '', ' - '); ?><?php $this->options->title(); ?><?php if ($this->is('index')) : ?> - <?php if(CommentTracks::output()->to($tracks)->have()){echo $tracks->author(false) . " 欢迎归来";}else{echo "我寻找着更多闪闪的星";} ?><?php endif; ?></div>
        <div class="role-body-class"><?php if($this->is('post') || $this->is('category')){echo $this->category;}else{echo 'home';} ?></div>
        <div class="role-category"><?php
        if($this->is('index')){echo 'home';}else
        if($this->is('category', 'tech')){echo 'tech';}else
        if($this->is('category', 'share')){echo 'share';}else
        if($this->is('category', 'news')){echo 'news';}else
        if($this->is('category', 'life')){echo 'life';}else
        if($this->is('category', 'diary')){echo 'diary';}else{
        } ?></div>
        <div class="role-head-title"><h1><?php if($this->is('index')){$this->options->title();}elseif ($this->is('post')){$this->archiveTitle(' &raquo; ', '', '');}else{$this->options->title(); echo ' &raquo '; $this->archiveTitle(' &raquo; ', '', '');} ?></h1></div>
    </div>
</div>
<?php endif; ?>
<div class="cont-wrap">