<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo "<a href=\"".U('Wei/Index/index')."\">weixin</a>";
    }
}