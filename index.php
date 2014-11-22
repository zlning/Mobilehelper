<?php

define("TOKEN", "wenbo");
include 'connect_mysql.php';
include 'connect_yidong.php';
include 'select_taocan.php';

$wechatObj = new wechatCallbackapiTest();


if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}



class wechatCallbackapiTest 
{   
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    private function checkSignature()
    {                                                                     
        $signature = $_GET["signature"];                 
        $timestamp = $_GET["timestamp"];                 
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    //接收的主界面
     public function responseMsg()
    {
         $time = time();
         $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
         if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            $state = FindUser($fromUsername);
            $OrignState = $state;
            $state = $state % 10;
            $msgType = "text";
            $datedata  = date("Y-m-d-H:i:s",time());
            switch($state)
            {
                case "0":
	            	switch($keyword)
    	       	 	{
        	        	case "0" :
	            	        $contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 0.菜单 \n";
    	            		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	    		break;
	                	case "1" :
                        	ChangeState($fromUsername,'1');
	    	             	$contentStr = "请输入您要查询的城市：\n";
	                 		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    		            	break;
                        case "2" :
                        	ChangeState($fromUsername,'2');
	    	             	$contentStr = "请输入您的手机号码：\n";
	                 		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    		            	break;
                        case "3":
                        	ChangeState($fromUsername,'3');
	    	             	$contentStr = "请输入您希望的通话量(分钟)：\n";
	                 		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    		            	break;
                		default :
                        	$RX_TYPE = trim($postObj->MsgType);
            				switch ($RX_TYPE)
            				{
                				case "event":
                					$result = $this->receiveEvent($postObj);
                    				break;
                				case "text":
                    				$msgType = "text";
    	            				$contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 0.菜单 \n";
        	        				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    				break;
            				}
            		}
                	break;
                case "1":
                	if($keyword==4){
                    	ChangeState($fromUsername,'0');
                    	$contentStr = $keyword."1";
                		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	}
                	else{
                        ChangeState($fromUsername,'0');
                        $this->responseCityWeather($postStr);
                    }
                	break;
                case "2":
                	$OrignState = ($OrignState-$state)/10;
                	$state = $OrignState % 10;
                	if($keyword==0){
                    	ChangeState($fromUsername,'0');
                    	$msgType = "text";
                    	$contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 0.菜单 \n";
                		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	}
                	else{
                        switch($state)
                        {
                            case 0:
                        		ChangeState($fromUsername,'12');
                        		ChangeKey($fromUsername,"mobile",$keyword);
                        		CheckIn_Yidong($keyword,$fromUsername,$datedata);
                				$picTpl = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<ArticleCount>1</ArticleCount>
									<Articles>
									<item>
									<Title><![CDATA[%s]]></Title>
									<Description><![CDATA[%s]]></Description>
									<PicUrl><![CDATA[%s]]></PicUrl>
                            		<Url><![CDATA[%s]]></Url>
									</item>
									</Articles>
									<FuncFlag>1</FuncFlag>
									</xml> ";
                        		$msgType = "news";
								$title = "验证码";
								$data  = date('Y-m-d');
								$desription = "点击打开图片，然后回复验证码";
								$image = "http://mobilehelp-test.stor.sinaapp.com/".$fromUsername.$datedata.".jpg";
                        		$turl = "http://mobilehelp-test.stor.sinaapp.com/".$fromUsername.$datedata.".jpg";
                				$resultStr = sprintf($picTpl, $fromUsername, $toUsername, $time, $msgType, $title,$desription,$image,$turl);
                            	break;
                          case 1:
                            	ChangeState($fromUsername,'22');
                            	ChangeKey($fromUsername,"yanzheng",$keyword);
                    			$contentStr ="请输入服务号\n";
                				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            	break;
                          case 2:
                            	ChangeState($fromUsername,'0');
                    			$contentStr =$keyword;
                				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            	break;
                        }
                    }
                	break;
                case "3":
                	$OrignState = ($OrignState-$state)/10;
                	$state = $OrignState % 10;
                	if($keyword==4){
                    	ChangeState($fromUsername,'0');
                    	$contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 0.菜单 \n";
                		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	}
                	else{
                        switch($state)
                        {
                            case 0:
                            	ChangeState($fromUsername,'13');
                        		ChangeKey($fromUsername,"tonghua",$keyword);
	    	            		$contentStr = "请输入您希望的流量（M）：\n";
                            	break;
                            case 1:
                            	ChangeState($fromUsername,'23');
                        		ChangeKey($fromUsername,"liuliang",$keyword);
	    	            		$contentStr = "请输入您希望的短信量（条）：\n";
                            	break;
                            case 2:
                            	ChangeState($fromUsername,'33');
                        		ChangeKey($fromUsername,"duanxin",$keyword);
                            	$contentStr = "选择公司：\nYD:移动\nLT:联通";
                            	break;
                            case 3:
                            	$contentStr = $keyword;
                            	if($keyword=='YD'||$keyword=='yD'||$keyword=='Yd'||$keyword=='yd')
                                {
        							$contentStr = YD_SelectBestTaocan($fromUsername);
                                }
                            	else if($keyword=='LT'||$keyword=='Lt'||$keyword=='lT'||$keyword=='lt')
                                {
                                    $contentStr = LT_SelectBestTaocan($fromUsername);
                                }
                            	else
                                {
                                    $contentStr = "不返回最佳套餐";
                                }
                            	 
                            	ChangeState($fromUsername,'0');
                            	break;
                            default:
                            	$contentStr = "Error\n";
                        }
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    }
                break;
             }
            echo $resultStr;
        }else{
            echo "";
            exit;
        }
    }
    
    
    private function responseCityWeather($postStr)
    {
         $time = time();
         $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
         if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            $result = $this->receiveText($postObj);
            echo $result;
        }else{
            $msgType = "text";
            $contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 4.菜单 \n";
         	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
            exit;
        }
        return 0;
    }
             
     private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎加入iFu工作室 ";
                break;
        }
        $result = $this->transmitText($object, $content);
        return $result;
    }
    
    
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        if(key)
        $url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword); 
        $output = file_get_contents($url);
        $content = json_decode($output, true);
        $result = $this->transmitNews($object, $content);
        return $result;
    }
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    	</item>
		";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<Content><![CDATA[]]></Content>
		<ArticleCount>%s</ArticleCount>
		<Articles>
		$item_str</Articles>
		</xml>";
        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

        
}
?>