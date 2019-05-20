<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
        <style>
header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;     
}
section {

    width:1400px;
    float:left;
    padding:20px; 
    text-align:center;       
}
#class td /*设置表格文字左右和上下居中对齐*/ 
{  
    vertical-align: middle;
    text-align: center;  
}
</style>
<head>
<header>
<h1 align = "center">用户登陆系统</h1>
<header>
<body>
    <section>
    <div id="class" align="center" style= "margin: 0cm 0cm 0pt; text-align: left">
    <form action = "" method = "POST">
    	<table align = "center">
    <tr>
    <td>username</td> 
    <td><input name = "username" type = "text"><br /></td>
	</tr>
	<tr>
    <td>password</td> 
    <td><input name = "password" type = "password"><br /></td>
	</tr>
    <tr>
    <td colspan="2"><input type = "submit" value = "submit" name = "login"></td></tr>
</table>
	</form>
</div>
</section>
</table>	
</body>
</html>