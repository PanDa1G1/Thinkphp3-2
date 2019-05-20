<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller
{
	public function index(){
	    $this->assign();
        $this->display();
        $name = I("post.username");
        $password = I("post.password");
        $test = M('users');
        $result = $test->where("name=%d and password='%s'",array($name,$password))->select();
        if(!$result){
            if($name != '')
            {
        	   die("error! please check your input!! ");
            }
        }
        
        else{
        	if($name == "admin"){
        		header("location:http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/admin");
        	}
        	else{
        		header("location:http://127.0.0.1/shenji/thinkphp3.2/thinkphp/index.php/home/guest");
        	}
        }
    }

	
} 
?>