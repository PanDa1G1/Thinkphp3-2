<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;	 
}
nav {
    line-height:30px;
    background-color:#eeeeee;
    height:300px;
    width:100px;
    float:left;
    padding:5px;	      
}
section {

    width:320px;
    float:left;
    padding:10px;	 	 
}

</style>
</head>
<header>
	<h1>服务端系统管理</h1>
</header>

<section>
<h1>系统设置</h1>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/add_ghs">添加供货商信息</a><br><br>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/change_ghs">修改供货商信息</a><br><br />
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/delete_ghs">删除供货商信息</a><br><br />
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/add_sp">添加商品信息</a><br><br>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/change_sp">修改商品信息</a><br><br />
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/delete_sp">删除商品商信息</a><br><br />
</section>

<section>
	<h1>商品登记入库</h1>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/jinhuo">商品入库</a><br>
</section>

<section>
	<h1>用户管理</h1>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/add_user">用户添加</a><br /><br />
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/delete_user">用户删除</a><br /><br />
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/privilege_change">权限划分</a><br /><br />
</section>
<section>
	<h1>数据备份</h1>
<a href = "http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin/exportDatabase">点我备份</a><br /><br />
<a></a><br />
</section>


</html>