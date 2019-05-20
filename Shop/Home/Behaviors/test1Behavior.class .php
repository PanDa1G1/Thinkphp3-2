<?php
namespace Home\Behaviors;
use Think\Behavior;
class TestBehavior extends Behavior
{
    public function run(&$params)
    {
        echo "Hook_testing!";
    }
}
?>