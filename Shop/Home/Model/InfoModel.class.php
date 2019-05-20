<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 18:58
 */
namespace HOME\Model;
use Think\Model;
class InfoModel extends Model
{
    protected $fields = array('id', 'username', 'email', 'age');
    protected $pk= 'id';
}