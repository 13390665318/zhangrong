<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<HTML>
<HEAD>
<TITLE>后台管理系统</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META http-equiv=Pragma content=no-cache>
<META http-equiv=Cache-Control content=no-cache>
<META http-equiv=Expires content=-1000>
<LINK href="/public/admin/css/admin.css" type="text/css" rel="stylesheet">
</HEAD>
<FRAMESET border=0 frameSpacing=0 rows="120, *" frameBorder=0>
<FRAME name=header src="<?php echo U('Index/header');?>" frameBorder=0 noResize scrolling=no>
<FRAMESET cols="275,*">
<FRAME name=menu src="<?php echo U('Index/menu');?>" frameBorder=0 noResize scrolling=yes>
<FRAME name=main src="<?php echo U('Index/main');?>" frameBorder=0 noResize scrolling=yes>
</FRAMESET>
</FRAMESET>
<noframes>
</noframes>
</HTML>