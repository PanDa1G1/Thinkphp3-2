<?php
namespace Home\Controller;
use Think\Controller;
use Think\Hook;
class SunController extends Controller
{
    public function index()
    {
       var_dump(C('DB_NAME'));
    }
}
?>
