<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html

PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >

<html>
<head>
<title>主页</title>
</head>
<body>
<center>
欢迎你,<?php echo ($name); ?>!
<a href="/yzm/index.php/Home/Update/index.html">修改信息</a>
<br><br><br>
<div id='time'></div><br>
  <a href="/yzm/index.php/Home/User/export" target="_blank">导出excel</a><br>
  <a href="/yzm/index.php/Home/Search/pdf">导出pdf</a>
  <br>
  <br>

  <form action="/yzm/index.php/Home/User/eximport" enctype="multipart/form-data" method="post" >
    <input type="file" name="excel" />
    <input type="submit" value="提交" >
  </form>
  <br><br>
  <a href="/yzm/index.php/Home/Sousou/index.html">查找用户</a><br>
  <a href="/yzm/index.php/Home/Search/search">所有用户信息（分页显示）</a><br><br>

<a href="/yzm/index.php/Home/Login">退出</a>

</center>
</body>
</html>