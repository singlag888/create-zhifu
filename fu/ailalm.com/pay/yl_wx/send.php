<?php
error_reporting(0);
require_once 'inc.php';

require_once 'HttpClient.class.php';

function sign($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$val;
	}
	//去掉最后一个&字符
	//$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}



//测试环境地址:https://devapi.tfcpay.com/v2/netpay
//生产环境地址:https://api.tfcpay.com/v2/netpay

$key			=	$userkey;		//MD5密钥，安全检验码


$data['mid']				=		$userid;  //商户号
$data['orderNo']			=		$_GET['orderid']; //商户订单号
$data['amount']				=		$_GET['price'];
$data['type']				=		"wechat";

$data['notifyUrl']			=		"http://pay.xiaowar.com/pay/yl_wx/notifyUrl.php";			

$data['subject']			=		"pay";
$data['body']				=		"pay";


$data['noise']				=		"123123ed".time();


ksort($data);


//print_r($data);




$md5						=		md5(createLinkstring($data)."&".$key);



//$md5						=		bin2hex($md5);


$sign						=		strtoupper($md5);




$data['sign']				=		$sign;


$url						=		"https://api.tfcpay.com/v2/pay/paycode";




// 调用
$header						= array();

$response					= curl_https($url, $data, $header, 5);



$web						=json_decode($response);



if ($web->msg=='ok'){



	$url		=$web->qrCode;
	
	$orderno	=$data['orderNo'];

	$amt		=$data['amount'];


}else{

	echo $web->errCodeDes;
	exit;

}






/** curl 获取 https 请求
* @param String $url        请求的url
* @param Array  $data       要發送的數據
* @param Array  $header     请求时发送的header
* @param int    $timeout    超时时间，默认30s
*/
function curl_https($url, $data=array(), $header=array(), $timeout=30){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response = curl_exec($ch);

    if($error=curl_error($ch)){
        die($error);
    }

    curl_close($ch);

    return $response;

}





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>微信扫码</title>
    <link href="css/style.css" type="text/css" rel="stylesheet" />
    <link href="css/css.css" type="text/css" rel="stylesheet" />
    <!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script>


    <!--<script src="http://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>-->
    <script src="js/Base64.js"></script>
    <script src="js/fingerprint2.js"></script>

</head>
<body>
    <div class="sweep">
        <div class="wrap">
            <div class="h100" id="res">
                <div class="m26">
                    <h1><div id="msg">订单提交成功，请您尽快付款！</div></h1>
                    <div class="num"><span><font color='Red' size='4px'>订单<?php echo $orderno?></font></span><span class="color1 ml16">使用手机登陆微信扫描二维码</span></div>
                </div>
            </div>
            <!--订单信息代码结束-->
            <!--扫描代码-->
            <div class="s-con" id="codem">
                <div class="title">
                    <span class="blue" style="font-size:20px;">
                        <span>应付金额：</span><span class="orange"><?php echo $amt?></span> 元
                        <br /><span style="font-size:12px;">支付</span>
                    </span>
                </div>
                <div class="scan">
                    <div id="divQRCode" class="divQRCode"></ div ></div>
                    <div class="question">
                        <div class="new"></div>
                    </div>
                </div>
                <div id="yzchdiv">
                    <input id="orderno" type="hidden" value="<?php echo $orderno?>" />
                    <input id="hidUrl" type="hidden" value="<?php echo $url?>" />
                </div>
                <!--扫描代码结束-->
                <!--底部代码-->
                <div class="s-foot">   Copyright?2016-2017 All Rights Reserved.</div>
                <!--底部代码结束-->
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {


        var hdurl = $('#hidUrl').val();



        var isIe = /msie/.test(navigator.userAgent.toLowerCase());

        // alert(isIe);

        var temp = 'canvas';
        if (isIe) {
            temp = 'table';
        }

        var fp = new Fingerprint2();
        fp.get(function(result) {
            if (typeof window.console !== "undefined") {

                console.log(result);
            }
            var orderno = $('#orderno').val();


            if (hdurl != null && hdurl != '') {
                //hdurl = BASE64.decoder(hdurl);
               
                $('#divQRCode').qrcode({
                    render: temp, //table方式
                    width: 288, //宽度
                    height: 288, //高度
                    text: hdurl //任意内容
                });
                if (temp == 'table') {
                    $('#divQRCode').css('top', '-136px');
                    $('#divQRCode').css('left', '239px');
                }
            }



        });


        refresh();
        function refresh() {
            var orderno = $('#orderno').val();
            $.ajax({
                url: 'returnUrl.php?ordernumber=' + orderno,
                type: 'GET',
                cache: false,
                success: function(data) {
                    if (data == "T"){
					
					
					}else{

						if (data.indexOf('status=1')>5){
						
								window.location = data;
						}
					
					}
                       
                }
            });
        }
        setInterval(refresh, 3000);
    });
</script>