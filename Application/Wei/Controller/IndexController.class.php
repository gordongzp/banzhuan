<?php
namespace Wei\Controller;
use Think\Controller;
class IndexController extends Controller {
	private $WxObj;
	private $appid='wx29e917ea71e86aca';
	private $secret='62361eae501395b554849d888141c39d';
	private $accessToken;
	public function valid(){
		//获取getAccessToken
		$this->getAccessToken();
		$echoStr = $_GET["echostr"];
        //valid signature , option
		if($this->checkSignature('weixin')){
			echo $echoStr;
			exit;
		}
		$this->WxObj=$this->getWxObj();

		//处理关键词回复
		if ($this->isText()) {
			switch (trim($this->WxObj->Content)) {
				case '1':
				$this->responseMsg('fu');
				break;
				case '2':
				$this->responseMsg('fu');
				break;
				
				default:
				break;
			}
		}

		//创建菜单
		$this->createNav();

	}






	/*
	获取消息类型
	*/
	private function isText($token){
		if($this->WxObj->MsgType=='text'){
			return true;
		}else{
			return false;
		}
	}

	/*
	获取用户消息对象
	*/
	private function getWxObj($token){
		//获取xml
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		file_put_contents('xml.php', $postStr);

		// <xml>
		// <ToUserName><![CDATA[gh_9ebf7f6b9fd1]]></ToUserName>
		// <FromUserName><![CDATA[oAmRZs9ba59XngHSjOedr92PnR_s]]></FromUserName>
		// <CreateTime>1465022813</CreateTime>
		// <MsgType><![CDATA[text]]></MsgType>
		// <Content><![CDATA[鍚庢灉]]></Content>
		// <MsgId>6292225070137921424</MsgId>
		// </xml>

		libxml_disable_entity_loader(true);
		// xml转化成php对象
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		return $postObj;
	}


	/*
	返回消息
	*/
	private function responseMsg($msg){
		$WxObj=$this->WxObj;
		$ToUserName=$WxObj->FromUserName;
		$FromUserName=$WxObj->ToUserName;
		$CreateTime=time();
		$str=<<<str
		<xml>
			<ToUserName><![CDATA[{$ToUserName}]]></ToUserName>
			<FromUserName><![CDATA[{$FromUserName}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[{$msg}]]></Content>
		</xml>
str;
		echo $str;die;
	}


	/*
	创建自定义菜单
	*/
	private function createNav(){
		$data = array(
			'button' => array(
					array('type'=>'click','name'=>'今日歌曲','url'=>'http://www.soso.com/'),
				), 
			);
		// 主意这里josn数据中文内容不转换
		$json=json_encode($data,JSON_UNESCAPED_UNICODE);
		
		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->accessToken;
		$result=$this->post($url,$json);
		return $result;

	}



	/*
	获取accessToken
	*/
	private function getAccessToken(){
		if (S('access_token')) {
			$this->accessToken=S('access_token');
			return true;
		}
		$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
		$json=$this->get($url);
		// 如果直接使用，返回的是一个对象，如果要获得数字就必须传入第二个参数
		$data=json_decode($json,true);
		//判断返回码是否有错误
		if (arraay_key_exists($data,'errcode')&&0!=$data['errcode']) {
			return false;
		}
		$accessToken=$data['access_token'];
		// 缓存数据...秒
		S('access_token',$accessToken,7000);
		$this->accessToken=$accessToken;
		return true;
	}

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




	/**
	提交POST数据
	**/
	public function post($url, $postData)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		if (!curl_exec($ch))
		{
			Log::write(curl_errno($ch));
			$data = '';
		}
		else
		{
			$data = curl_multi_getcontent($ch);
		}
		curl_close($ch);
		return $data;
	}



	/**
	请求服务器
	**/
    public function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ( ! curl_exec($ch))
        {
            Log::write(curl_errno($ch));
            $data = '';
        }
        else
        {
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        return $data;
    }
}

