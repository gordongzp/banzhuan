<?php 



function curl_post ( $url ,  $param = array ()){

if (! is_array ( $param )){

throw   new   Exception ( "参数必须为array" );

}

$httph  = curl_init ( $url );

curl_setopt ( $httph ,  CURLOPT_SSL_VERIFYPEER ,   0 );

curl_setopt ( $httph ,  CURLOPT_SSL_VERIFYHOST ,   1 );

curl_setopt ( $httph , CURLOPT_RETURNTRANSFER , 1 );

curl_setopt ( $httph ,  CURLOPT_USERAGENT ,   "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)" );

curl_setopt ( $httph ,  CURLOPT_POST ,   1 ); //设置为POST方式 

curl_setopt ( $httph ,  CURLOPT_POSTFIELDS ,  $param );

curl_setopt ( $httph ,  CURLOPT_RETURNTRANSFER , 1 );

curl_setopt ( $httph ,  CURLOPT_HEADER , 1 );

$rst = curl_exec ( $httph );

curl_close ( $httph );

return  $rst ;

}
