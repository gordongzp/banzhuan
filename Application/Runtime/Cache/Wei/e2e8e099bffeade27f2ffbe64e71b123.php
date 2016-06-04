<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html> <html>     
<head>            
	<title>WeUI</title>         
	 <meta charset="UTF-8">        
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">    
 <link rel="stylesheet" href="/Public/weui/dist/style/weui.min.css"/>   
</head>    
<body>


<div id="actionSheet_wrap">
   <div class="weui_mask_transition" id="mask"></div>
   <div class="weui_actionsheet" id="weui_actionsheet">
       <div class="weui_actionsheet_menu">
           <div class="weui_actionsheet_cell">示例菜单</div>
           <div class="weui_actionsheet_cell">示例菜单</div>
           <div class="weui_actionsheet_cell">示例菜单</div>
           <div class="weui_actionsheet_cell">示例菜单</div>
       </div>
       <div class="weui_actionsheet_action">
           <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
       </div>
   </div>
</div>


</body> 
</html>