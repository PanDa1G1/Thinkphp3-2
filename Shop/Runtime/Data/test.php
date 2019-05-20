<?php

class maker_q
{
	public $q1;
	private $q2;
	function __construct()
	{
		$this->q1 = $this->q2;
	}
	function __call($method, $args)
	{
		$this->q1->execute();
	}

}

class maker_e
{
	public $e1;
	private $e2;

	function __construct()
	{
		$this->e1 = "session";
	}
	function __get($data)
	{
		system('ls');
	}

	function __invoke()
	{
		echo "Do you understand".$this->e1;
	}
}


class maker_w
{
	public $w1;
	private $w2;

	function __construct()
	{
		$this->w1 = 'hhhh';
	}

	function __toString()
	{
		$a1 = $this->w1;
		$a1->test0();
		return "1";
	}
}



class get_flag
{
	public $get1;
	protected $get2;

	function execute()
	{
		include('123.txt');
	}
}

class maker_r
{
	public $r1;
	public $r2;

	function __destruct()
	{
		$s1 = $this->r2;
		$s1();
	}

	function __sleep()
	{
		return array("maker","ctf");
	}
	
}

function func_1($data1)
{
        echo "welcome to makerctf today is " . date("Y/m/d") . "<br />";
        echo "your input is",var_dump($data1);
}

$func_0 = "func_1";
call_user_func($_GET['func'],$_GET);
if(!isset($_POST['s']))
{
        $func_0();
}
else
{
        $func_0($_POST);
}

?>
