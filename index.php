<?php

define("TOKEN", "wenbo");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}
class wechatCallbackapiTest
{
    $flag = 0;
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
            
            
            switch($keyword)
            {
                case "4" :
                	$msgType = "text";
                    $contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询 \n 4.menu";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               	 	echo $resultStr;
                	break;
                
                case "1" :
                	global $flag = 1;
                	$msgType = "text";
                 	$contentStr = "请输入你要查询的城市：\n";
                 	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                 	echo $resultStr;
                	echo sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, global $flag);
                	break;
                
                case "2" :
                case "3" :
                	$msgType = "text";
                	$contentStr = date("Y-m-d H:i:s",time())."\nSorry :ERROR.";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               	 	echo $resultStr;
                	break;
                
                default :
                    $msgType = "text";
                	$contentStr = date("Y-m-d H:i:s",time())."\n欢迎来到Mobilehelper,我们为大家提供了如下的服务：\n 1.天气查询  \n 2.话费查询  \n 3.套餐最优查询";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               	 	echo $resultStr;
            }

        }else{
            echo "";
            exit;
        }
    }
    
    
    public function responseCityWeather()
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
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {
                case "event":
                	$result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else{
            echo "";
            exit;
        }
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
    private function logger($log_content)
    {

    }
        
}
?>