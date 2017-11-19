<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2017/11/14
 * Time: 15:08
 */
namespace Home\Controller;
use Think\Controller;
class sousouController extends Controller
{
    public function index()
    {
        $this->display();//显示页面
    }
    public function sousou(){
        if(isset($_POST['username']) && $_POST['username']!=null)
        {
            $where['username']=array('like',"{$_POST['username']}");
        }
        $m=M("imformation");
        $arr=$m->where($where)->select();
        $this->assign('data',$arr);
        $this->display('index');
    }
}