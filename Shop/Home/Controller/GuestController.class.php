<?php
namespace Home\Controller;
use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
error_reporting(0);
class GuestController extends Controller
{
	public function index(){
		$this->assign();
		$this->display();
	}
	public function shangpin_type(){
		$table = M('商品');
		$res = $table->select();
		$count = $table->where()->count();
		echo '<table border="1" width="960px" align="center"><tr><td>商品种类</td><td>库存</td></tr>';
		for($i=0;$i<$count;$i++)
		{
			$this->assign($res[$i]);
			$this->display('shangpin_type');
		}
	}
	public function gonghuoshang(){
		$table = M('商品');
		$res = $table->field('商品.suppler_num,商品.kucun_count, 供货商.name')->join('供货商 ON 商品.suppler_num = 供货商.number')->select();
		$count = $table->where()->count();
		echo '<table border="1" width="960px" align = "center"><tr><td >供货商</td><td>供货商编号</td><td>库存</td></tr>';
		
		for($i=0;$i<$count;$i++)
		{
			$this->assign($res[$i]);
			$this->display('gonghuoshang');
		}
	}
	public function sell_shangpin(){
		$this->display('sell_shang_name');
		$name = I('get.shang_name');
		$sell_count = I('get.shang_num');
		$xioashouyuan = I('get.xiaoshouyuan');
		$fapiao_num = I('get.fapiao_num');
		if($fapiao_num != '' ){
		$shang = M('商品');
		$count = $shang->field('kucun_count')->where("shangpin_name='%s'",array($name))->select();
		$count = $count[0]["kucun_count"] -  $sell_count;
		$data['kucun_count'] = $count;
		$shang->where("shangpin_name='%s'",array($name))->save($data);
		$shang_1 = $count = $shang->where("shangpin_name='%s'",array($name))->select();
		$ticket = M('销售信息表');
		$data['fapiao_num'] = $fapiao_num;
		$data['shangpin_num'] = $shang_1[0]['shangpin_num'];
		$data['shangpin_name'] = $name;
		$data['xiaoshou_price'] = $shang_1[0]['shangpin_price'];
		$data['xiaoshou_count'] = $sell_count;
		$data['pay_money'] = $shang_1[0]['shangpin_price'] * $sell_count;
		$data['date'] = date("Y/m/d");
		$data['xiaoshouyuan'] = $xioashouyuan;
		$ticket->add($data);
		$res2 = $ticket->where("fapiao_num='%s'",array($fapiao_num))->select();
		echo '<table border="1" width="960px" align = "center"><tr><td colspan = "2">发票</td></tr>';
		$this->assign($res2[0]);
		$this->display('ticket');
		}
	}
	public function jinhuo()
	{
		$jinhuo = M('进货');
		$res = $jinhuo->select();
		echo '<table border="1" width="960px" align = "center"><tr><td colspan = "2">进货单</td></tr>';
		$count = $jinhuo->count();
		for($i = 0;$i < $count;$i++)
		{
			$this->assign($res[$i]);
			$this->display('jinhuo');
		}
		
	}
	public function selling(){
		$selling = M('销售信息表');
		$res = $selling->select();
		echo '<table border="1" width="960px" align = "center"><tr><td colspan = "2">销售信息表</td></tr>';
		$count = $selling->count();
		for($i = 0;$i < $count;$i++)
		{
			$this->assign($res[$i]);
			$this->display('sell_message');
		}
	}
	public function shangpin_message(){
		$shangpin = M('商品');
		$res = $shangpin->select();
		echo '<table border="1" width="960px" align = "center"><tr><td colspan = "2">商品信息表</td></tr>';
		$count = $shangpin->count();
		for($i = 0;$i < $count;$i++)
		{
			$this->assign($res[$i]);
			$this->display('shangpin_message');
		}
	}

}