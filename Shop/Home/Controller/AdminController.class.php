<?php
namespace Home\Controller;
use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
class AdminController extends Controller{
	public function index(){
		$this->assign();
		$this->display();
	}
	public function add_sp(){
		$this->display('add_sp');
		$data['shangpin_num'] = I('post.shangpin_num');
		$data['shangpin_name']  = I('post.shangpin_name');
		$data['shangpin_type']  = I('post.shangpin_type');
		$data['shangpin_price']  = I('post.shangpin_price');
		$data['shangpin_guige']  = I('post.shangpin_guige');
		$data['kucun_count']  = I('post.kucun_count');
		$data['suppler_num']  = I('post.suppler_num');
		$table = M('商品');
		if($data['shangpin_num'] != '' && $table->add($data)){
			echo "成功添加！";
		}
	}
	public function change_sp(){
		$this->display('change_sp');
		$data['shangpin_num'] = I('post.shangpin_num');
		$data['shangpin_name']  = I('shangpin_name');
		$data['shangpin_type']  = I('post.shangpin_type');
		$data['shangpin_price']  = I('post.shangpin_price');
		$data['shangpin_guige']  = I('post.shangpin_guige');
		$data['kucun_count']  = I('post.kucun_count');
		$data['suppler_num']  = I('post.suppler_num');
		$table = M('商品');
		if($table->where("shangpin_num='%s'",array($data['shangpin_num']))->save($data)){
			echo "成功修改！";
		}
	}
	public function delete_sp(){
		$this->display('delete_sp');
		$data['shangpin_num'] = I('post.gh_num');
		$table = M('商品');
		if($data['shangpin_num'] != '' && $table->where("shangpin_num='%s'",array($data['shangpin_num']))->delete()){
			echo "成功删除！";
		}
	}
	public function add_ghs(){
		$this->display('add_ghs');
		$data['number'] = I('post.gh_num');
		$data['name']  = I('post.gh_name');
		$data['address']  = I('post.gh_address');
		$data['lianxiren']  = I('post.gh_lianxiRen');
		$data['lianxi_way']  = I('post.gh_lianxiWay');
		$table = M('供货商');
		if($data['number'] != '' && $table->add($data)){
			echo "成功添加！";
		}
	}
	public function change_ghs(){
		$this->display('change_ghs');
		$data['number'] = I('post.gh_num');
		$data['name']  = I('post.gh_name');
		$data['address']  = I('post.gh_address');
		$data['lianxiren']  = I('post.gh_lianxiRen');
		$data['lianxi_way']  = I('post.gh_lianxiWay');
		$table = M('供货商');
		if($table->where("number='%s'",array($data['number']))->save($data)){
			echo "成功修改！";
		}
	}
	public function delete_ghs(){
		$this->display('delete_ghs');
		$data['number'] = I('post.gh_num');
		$table = M('供货商');
		if($data['number'] != '' && $table->where("number='%s'",array($data['number']))->delete()){
			echo "成功删除！";
		}
	}
	public function jinhuo(){
		$this->display('jinhuo');
		$data['huodan_num'] = I('post.huodan_num');
		$data['supplier_num']  = I('post.supplier_num');
		$data['shangpin_num']  = I('post.shangpin_num');
		$data['count']  = I('post.count');
		$data['price']  = I('post.price');
		$data['date']  = I('post.date');
		$shangpin = M('商品');
		$res = $shangpin->where("shangpin_num='%s'",array($data['shangpin_num']))->select();
		$data1['shangpin_num'] = $data['shangpin_num'];
		$data1['shangpin_name']  = $res[0]['shangpin_name'];
		$data1['shangpin_type']  = $res[0]['shangpin_type'];
		$data1['shangpin_price']  = $data['price'];
		$data1['shangpin_guige']  = $res[0]['shangpin_guige'];
		$data1['kucun_count']  = $data['count'] + $res[0]['kucun_count'] ;
		$data1['suppler_num']  = $data['supplier_num'];
		$table = M('进货');
		if($data['huodan_num'] != '' && $table->add($data) && $shangpin->where("shangpin_num='%s'",array($data['shangpin_num']))->save($data1))
		{
			echo "成功入库！";
		}
	}
	public function add_user(){
		$this->display('add_user');
		$data['name'] = I('post.username');
		$data['password']  = I('post.password');
		$data['type']  = I('post.privilege');
		$table = M('users');
		if($data['name'] != '' && $table->add($data)){
			echo "成功添加！";
		}
	}
	public function privilege_change(){
		$this->display('privilege_change');
		$data['name'] = I('post.username');
		$data['type']  = I('post.privilege');
		$table = M('users');
		if($table->where("name='%s'",array($data['name']))->save($data)){
			echo "成功修改权限！";
		}
	}
	public function delete_user(){
		$this->display('delete_user');
		$data['name'] = I('post.username');
		$table = M('users');
		if($data['name'] != '' && $table->where("name='%s'",array($data['name']))->delete()){
			echo "成功删除！";
		}
	}
	public function exportDatabase(){
	header("Content-type:text/html;charset=utf-8");
	$path = C('ROOT_MYSQL_PATH');
	$model = M();
	//查询所有表
	$sql="show tables";
	$result=$model->query($sql);
	//print_r($result);exit;
	//echo "运行中，请耐心等待...<br/>";
	$info = "-- ----------------------------\r\n";
	$info .= "-- 日期：".date("Y-m-d H:i:s",time())."\r\n";
	$info .= "-- MySQL - 5.1.73 : Database - ".C('DB_NAME')."\r\n";
	$info .= "-- ----------------------------\r\n\r\n";
	$info .= "CREATE DATAbase IF NOT EXISTS `".C('DB_NAME')."` DEFAULT CHARACTER SET utf8 ;\r\n\r\n";
	$info .= "USE `".C('DB_NAME')."`;\r\n\r\n";
	// 检查目录是否存在
	if(is_dir($path)){
	//echo '目录存在';
	// 检查目录是否可写
	if(is_writable($path)){
	//echo '目录可写';exit;
	}else{
	echo '目录不可写';exit;
	//chmod($path,0777);
	}
	}else{
	//echo '目录不存在';exit;
	// 新建目录
	mkdir($path, 0777, true);
	//chmod($path,0777);
	}
	// 检查文件是否存在
	$file_name = $path.C('DB_NAME').'-'.date("Y-m-d",time()).'.sql';
	if(file_exists($file_name)){
	echo "数据备份文件已存在！";
	exit;
	}
	file_put_contents($file_name,$info,FILE_APPEND);
	foreach ($result as $k=>$v) {
	$val = $v['tables_in_'.C('DB_NAME')];
	$sql_table = "show create table ".$val;
	$res = $model->query($sql_table);
	//print_r($res);exit;
	$info_table = "-- ----------------------------\r\n";
	$info_table .= "-- Table structure for `".$val."`\r\n";
	$info_table .= "-- ----------------------------\r\n\r\n";
	$info_table .= "DROP TABLE IF EXISTS `".$val."`;\r\n\r\n";
	$info_table .= $res[0]['create table'].";\r\n\r\n";
	//查询表数据
	$info_table .= "-- ----------------------------\r\n";
	$info_table .= "-- Data for the table `".$val."`\r\n";
	$info_table .= "-- ----------------------------\r\n\r\n";
	file_put_contents($file_name,$info_table,FILE_APPEND);
	$sql_data = "select * from ".$val;
	$data = $model->query($sql_data);
	//print_r($data);exit;
	$count= count($data);
//print_r($count);exit;
	if($count<1) continue;
	foreach ($data as $key => $value){
	$sqlStr = "INSERT INTO `".$val."` VALUES (";
	foreach($value as $v_d){
	$v_d = str_replace("'","\'",$v_d);
	$sqlStr .= "'".$v_d."', ";
	}
//需要特别注意对数据的单引号进行转义处理
//去掉最后一个逗号和空格
	$sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
	$sqlStr .= ");\r\n";
	file_put_contents($file_name,$sqlStr,FILE_APPEND);
	}
	$info = "\r\n";
	file_put_contents($file_name,$info,FILE_APPEND);
	}
	echo "OK!";
}

}