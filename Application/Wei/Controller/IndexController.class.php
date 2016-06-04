<?php
namespace Wei\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function valid(){
		$echoStr = $_GET["echostr"];
        //valid signature , option
		if($this->checkSignature('weixin')){
			echo $echoStr;
			exit;
		}
	}



/*
获取用户消息对象
*/

/**
验证token
**/
	private function checkSignature($token){
		/*获取微信发送确认的参数。*/
		$signature = $_GET['signature']; /*微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。*/
		$timestamp = $_GET['timestamp']; /*时间戳 */
		$nonce = $_GET['nonce']; /*随机数 */
		$echostr = $_GET['echostr']; /*随机字符串*/
		/*加密/校验流程*/
		/*1. 将token、timestamp、nonce三个参数进行字典序排序*/
		$array = array($token,$timestamp,$nonce);
		sort($array,SORT_STRING);
		/*2. 将三个参数字符串拼接成一个字符串进行sha1加密*/
		$str = sha1( implode($array) );
		/*3. 开发者获得加密后的字符串可与signature对比，标识该请求来源于微信*/
		if( $str==$signature && $echostr ){
			return ture;
		}else{
			return false;
		}
	}
}

