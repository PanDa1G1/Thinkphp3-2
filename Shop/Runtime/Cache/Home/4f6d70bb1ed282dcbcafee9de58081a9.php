<?php if (!defined('THINK_PATH')) exit();?><form action = "" method="POST" align = "center">
	<table align = "center">
	  <tr>
	  <td>进货单编号</td>  
	  <td><input type = "text" name = "huodan_num"><br /></td>
	  </tr>
	  <tr>
	  <td>输入供货商编号</td> 
	  <td><input type = "text" name = "supplier_num"><br /></td>
	  </tr>
	  <tr>
	  <td>输入商品编号 </td>
	  <td><input type = "text" name = "shangpin_num"><br /></td>
	  </tr>
	  <tr>
	  <td>输入商品价格</td>  
	  <td><input type = "text" name = "price"><br /></td>
      </tr>
      <tr>
	  <td>输入商品数量</td>  
	  <td><input type = "text" name = "count"><br /></td>
      </tr>
      <tr>
	  <td>输入进货时间</td>  
	  <td><input type = "text" name = "date"><br /></td>
      </tr>
		<td colspan = "2"><input type = "submit" name = "submit" value = "confirm"></td>
		</tr>
</form>