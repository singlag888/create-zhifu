<?php 
if(!isset($_SESSION)){ session_start();}
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
$C_Patch=$_SERVER['DOCUMENT_ROOT'];
include_once($C_Patch."/app/member/utils/login_check.php");
include_once($C_Patch."/include/newpage.php");
include_once($C_Patch."/app/member/class/user.php");
include_once($C_Patch."/app/member/cache/bank.php");

?>
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" /> 
    <script>
        var f_com = {};
        f_com.MChgPager = function(options, otherData) {

            var conf = $.extend(f_com.__options, options);

            var data = {"module": conf.module, "method": conf.method};
          /*  if(otherData) {
                $.extend(data, otherData);
            }
            if(conf.module=="MACenterView" && (conf.method=="bankTake" || conf.method=="bankATM" || conf.method=="bankSavings")){
                conf.blockUI = false;
            }
            if(conf.blockUI) {
                $(conf.blockId).block({
                    message: "<div id='MBlockImg'></div>",
                    centerY: 0,
                    css: {
                        "background-color": "transparent",
                        top: "300px",
                        width: '0px',
                        border: "none",
                        cursor: "default"
                    },
                    overlayCSS: {
                        cursor: "default",
                        backgroundColor: conf.maskColor
                    }
                });
            }*/
            $.ajax({
                type: conf.type,
                url: '/cl/index.php',
                data: data,
                dataType: conf.dataType,
                timeout: conf.timeout,
                error: function(data) {
                    alert(JsBook.S_MSG_TRAN_BUSY);
                },
                success: function(data) {
                    $('#MACenter-content').html(data);
                    alert('您已经成功提交，请等待工作人员审核！');
                    window.top.location.href = '/wap.php';
                },
                complete: function() {
                    if(conf.blockUI) {
                        $(conf.blockId).unblock();
                    }
                }
            });
        }
    </script>
	<script language="javascript" type="text/javascript">
		var clientW = document.documentElement.clientWidth;
		document.getElementsByTagName('html')[0].style.fontSize = clientW/3 + 'px';
	</script>
    <script language="javascript" type="text/javascript">

        var getId = function(Id){
            return document.getElementById(Id);
        }

        function next_checkNum_img(){
            document.getElementById("checkNum_img").src = "../yzm.php?"+Math.random();
            return false;
        }

        //数字验证 过滤非法字符
        function clearNoNum(obj){
            //先把非数字的都替换掉，除了数字和.
            obj.value = obj.value.replace(/[^\d.]/g,"");
            //必须保证第一个为数字而不是.
            obj.value = obj.value.replace(/^\./g,"");
            //保证只有出现一个.而没有多个.
            obj.value = obj.value.replace(/\.{2,}/g,".");
            //保证.只出现一次，而不能出现两次以上
            obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
            if(obj.value != ''){
                var re=/^\d+\.{0,1}\d{0,2}$/;
                if(!re.test(obj.value))
                {
                    obj.value = obj.value.substring(0,obj.value.length-1);
                    return false;
                }
            }
        }

        function showTypeTxt(t){
            if(t==1){
                getId('v_type').style.display='none';
            }else{
                getId('v_type').style.display='';
            }
        }

        function showType(){
            if(getId('InType').value=='0'){
                getId('v_type').style.display='';
                getId('tr_v').style.display='none';
            }else if(getId('InType').value=='网银转账'){
                getId('tr_v').style.display='';
                //getId('v_Name').value='请输入持卡人姓名';
                getId('v_type').style.display='none';
                getId('IntoType').value=getId('InType').value;
            }else{
                getId('v_type').style.display='none';
                getId('IntoType').value=getId('InType').value;
                getId('tr_v').style.display='none';
            }
        }

        function SubInfo(){
            var hk	=	getId('v_amount').value;
            if(hk==''){
                alert('请输入转账金额');
                getId('v_amount').focus();
                return false;
            }else{
                hk = hk*1;
                if(hk<100){
                    alert('转账金额最底为：100元');
                    getId('v_amount').select();
                    return false;
                }
            }
            if(getId('IntoBank').value==''){
                alert('为了更快确认您的转账,请选择转入银行');
                return false;
            }
            if(getId('cn_date').value==''){
                alert('请选择汇款日期');
                return false;
            }

            if(getId('InType').value==''){
                alert('为了更快确认您的转账,请选择汇款方式');
                return false;
            }
            if(getId('InType').value=='0'){
                if(getId('v_type').value!=''&& getId('v_type').value!='请输入其它汇款方式'){
                    getId('IntoType').value=getId('v_type').value;
                }else{
                    alert('请输入其它汇款方式');
                    return false;
                }
            }
            if(getId('InType').value=='网银转账'){
                if(getId('v_Name').value!=''&& getId('v_Name').value!='请输入持卡人姓名' && getId('v_Name').value.length>1 && getId('v_Name').value.length<20){
                    var tName =getId('v_Name').value;
                    var yy = tName.length;
                    for(var xx=0;xx<yy; xx++){
                        var zz = tName.substring(xx,xx+1);
                        if(zz!='·'){
                            if(!isChinese(zz)){
                                alert('请输入中文持卡人姓名,如有其他疑问,请联系在线客服');
                                return false;
                            }
                        }
                    }
                }else{
                    alert('为了更快确认您的转账,网银转账请输入持卡人姓名');
                    return false;
                }
            }
            if(getId('v_site').value==''){
                alert('请填写汇款地点');
                return false;
            }
            if(getId('v_site').value.length>49){
                alert('汇款地点不要超过50个中文字符');
                return false;
            }
            if(getId('vlcodes').value==''){
                alert('请输入验证码');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/cl/pages/huikuanDao.php?into=true",
                data:$('#form1').serialize()
            }).done(function( msg ) {
                    if(msg=="chakan_huikuan"){
                       // alert('您已提交成功，请等待工作人员审核！');
                        f_com.MChgPager({method:'chakan_huikuan'});
                    }else{
                        alert(msg);
                    }
                }).fail(function(error){
                    alert(error.responseText);
                });
        }

        function alertMsg(i){
            if(i==1){
                alert('阁下,您好:\n您已经成功提交一笔 汇款信息 未处理,请等待处理后再提交新的汇款信息! ');
                LoadValImg();
            }
            if(i==2){
                alert('汇款信息提交成功，请等待处理');
                window.location.href='ScanTrans.aspx';
            }
        }
        //是否是中文
        function isChinese(str){
            return /[\u4E00-\u9FA0]/.test(str);
        }

        function url(u){
            window.location.href=u;
        }
 
    </script>
    <style type="text/css">
        .dv{ line-height:25px;}
        .body2{
            width: 2.8rem;
            height: auto;
           margin:0 auto;
		   overflow:hidden;
        }
		td{ font-size:0.11rem;}
        .tds {
            line-height:25px;
        }
        .STYLE1 {font-weight: bold}
        .STYLE2 {color:rgb(235, 211, 97);}
        .STYLE12{ color:#F00}
		body{ font-size:0.14rem; color:#fff; background:#1A1309; height:100%;  font-family: "Arial","Microsoft YaHei","ºÚÌå","ËÎÌå",sans-serif; margin:0; }
		.siZe{ font-size:0.14rem; color:rgb(235, 211, 97); }
        #centers td{ text-align:left; height:0.2rem;}

		#centers .col{ text-align:right; }
        #centers .pad{ padding-top:0.05rem; }
html{ height:100%; background:#1A1309; }
#headerBox{ width:100%; height:0.35rem; line-height:0.35rem; background: #130d35; box-sizing:border-box; overflow:hidden;  position:relative;  }
#headerBox a{ padding:0.12rem; border-radius:0.12rem; background:rgba(0,0,0,0.3) url(../../img/index_ico.png) center center no-repeat; background-size:70%; position:absolute; left:0.1rem; top:calc(0.18rem - 0.12rem); }
#headerBox .logo{ display:block; position:absolute; left:calc(1.5rem - 0.46rem); top:calc(0.18rem - 0.16rem); height:0.3rem; }
	input,select{ height:0.2rem !important;; }
	
	#SubTran{width:0.7rem;0.25rem !important; border-radius:0.02rem; height:0.25rem !important;
       background:#e71516 !important;
    border: none;
    text-align:center;
    color: #fff;}
	#SubTran:hover{color:yellow;font-weight:bold}
    </style>

<header id="headerBox">
	<a href="../../wap.php"></a>
	<img class="logo" src="/pic/logo.png" />
</header>

<table id="centers" width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td width="2%" class="col pad" ><div style="padding-left:10px;line-height:30px;">建设银行：</div> </td>
                <td width="23%" class="pad"><span class="STYLE2">详情请与客服索取</span></td>    
            </tr>
            <tr>
                <td width="7%" class="col">开户名：</td>
                <td width="11%"><span class="STYLE2">*</span></td>
            </tr>
            <tr>
                <td width="14%"  class="col">开户地址：</td>
                <td>详情请与客服索取</td>
            </tr>
            <tr>
                <td width="2%" class="col pad"><div style="padding-left:10px;line-height:30px;">支付宝：</div> </td>
                <td width="23%" class="pad"><span class="STYLE2">详情请与客服索取</span></td>
               
                
            </tr>
            <tr>
                <td width="7%" class="col">开户名：</td>
                <td width="11%"><span class="STYLE2">*</span></td>
            </tr>
            <tr>
                <td width="14%" class="col">开户地址：</td>
                <td>详情请与客服索取</td>
            </tr>
            <tr>
            <td width="2%" class="col pad"><div style="padding-left:10px;line-height:30px;">微信账号：</div> </td>
            <td width="23%" class="pad"><span class="STYLE2">*</span></td>
           
           
        </tr>
        <tr>
            <td width="7%" class="col">开户名：</td>
            <td width="11%"><span class="STYLE2">太陽城集團</span></td>
        </tr>
        <tr>
            <td width="14%" class="col" style="padding-bottom:0.1rem;">开户地址：</td>
            <td style="padding-bottom:0.1rem;">详情请与客服索取</td>
        </tr>
    </tbody></table>

<div id="MACenterContent">
    <div id="MMainData">

<div class="body2">
<div style="overflow:hidden; width:100%;">

<table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
<tr>
<td width="100%" height="61" align="left" colspan="3">


<form id="form1" name="form1" action="huikuanDao.php?into=true" method="post">
<table width="100%" style="border-collapse:collapse;" border="0" cellpadding="6" cellspacing="1" >
<tr>
    <td height="30" align="right" style="width:30%;"> 用户帐号:</td>
    <td align="left"><span class="STYLE5">
                        &nbsp;&nbsp;&nbsp;<?=$_SESSION['username'];?>
                        </span></td>
</tr>
<tr>
    <td height="30" align="right"><span class="STYLE12">*</span> 存款金额:</td>
    <td align="left">&nbsp;&nbsp;&nbsp;<input name="v_amount" type="text" id="v_amount" style="border:1px solid #CCCCCC;height:100%;line-height:100%; width:60%;" onkeyup="clearNoNum(this);" size="15"/></td>
</tr>
<tr>
    <td height="30" align="right">
        <span class="STYLE12">* </span>汇款银行:</td>
    <td align="left">
        &nbsp;&nbsp;&nbsp;<select id="IntoBank" name="IntoBank" style="width:60%;height:100%;">
            <option value="" selected="selected">请选择转入银行</option>
            <?php 
            foreach($bank[1] as $k=>$arr){
                ?>
                <option value="<?=$arr['card_bankName']?>"><?=$arr['card_bankName']?></option>
            <?php 
            }
            ?>
        </select>
        </span> </td>
</tr>
<tr>
    <td height="30" align="right">
        <span class="STYLE12">* </span>汇款日期:</td>
    <td align="left" class="font-black">&nbsp;&nbsp;&nbsp;<input name="cn_date" type="text" id="cn_date"  size="10" maxlength="10" value="<?=date("Y-m-d",time())?>" style="border:1px solid #CCCCCC;height:100%;line-height:100%; width:15%;"/>
        时间：
        <select name="s_h" id="s_h" style="width:10%;height:100%;">
            <option value="00">00</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
        </select>
        时
        <select name="s_i" id="s_i" style="height:100%; width:10%;">
            <option value="00">00</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
        </select>
        分
        <select name="s_s" id="s_s" style="height:100%; width:10%;">
            <option value="00">00</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
        </select>秒</td>
</tr>
<tr>

    <td height="30" align="right">
        <span class="STYLE12">*</span> 汇款方式:</td>
    <td align="left">
                            <span class="STYLE5">
                            &nbsp;&nbsp;&nbsp;<select id="InType" name="InType" onchange="showType();" style="width:60%;height:100%;">
                                    <option value="">请选择汇款方式</option>
                                    <option value="银行柜台">银行柜台</option>

                                    <option value="ATM现金">ATM现金</option>
                                    <option value="ATM卡转">ATM卡转</option>
                                    <option value="网银转账" selected='selected'>网银转账</option>
                                    <option value="0">其它[手动输入]</option>
                                </select>
                            
                            <input id="v_type" name="v_type" type="text" size="19" value="请输入其它汇款方式" onfocus="javascript:getId('v_type').select();" class="font-hhblack" style="border:1px solid #CCCCCC;height:18px;line-height:20px; font-size:12px; display:none;" />
                            <input type="hidden" id="IntoType" name="IntoType" value="" />                        
                      </span></td>
</tr>
<tr id="tr_v" style="display:none;">
    <td height="30" align="right">
        <span class="STYLE12">*</span>持卡姓名:</td>
    <td align="left">&nbsp;&nbsp; <input name="v_Name" type="text" id="v_Name" style="border:1px solid #CCCCCC;height:100%;width:60%;line-height:100%;" onfocus="javascript:this.select();" size="34"  placeholder='请输入持卡人姓名'/></td>
</tr>
<tr>
    <td height="30" align="right">
        <span class="STYLE12">*</span> 汇款地点:</td>
    <td align="left"><span style="color: #999999">
                        &nbsp;&nbsp;
                        <input id="v_site" name="v_site" type="text" size="34" style="border:1px solid #CCCCCC;height:100%;line-height:100%;width:60%;" />
                     <!-- <span class="STYLE2" style="color: #333">(您汇款的所在地区)</span> --></span></td>
</tr>

<tr>

    <td height="35" align="right">
        <span class="STYLE12">* </span>验 证 码:</td>
    <td align="left" valign="middle"><table width="80%" style="height:0.16rem;">
            <tr><td class="STYLE5" style="padding-left:10px;"><input name="vlcodes" type="text" id="vlcodes" size="5" maxlength="4" onfocus="next_checkNum_img()" onkeyup="clearNoNum(this);" style="border:1px solid #CCCCCC;height:100%;line-height:100%; width:100%;"/></td><td>
                    <img src="../yzm.php" alt="点击更换" name="checkNum_img" id="checkNum_img" style="cursor:pointer; top:3px; height:100%;float:left;" onclick="next_checkNum_img()" />

                </td></tr></table>                        </td>
</tr>
<tr><td height="35" align="right">&nbsp;</td>
    <td height="40" align="left" valign="middle">
        &nbsp;&nbsp; <input name="SubTran" type="button" class="anniu_02" id="SubTran" onclick="SubInfo();" value="提交信息"  />					</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</div>
</div>
    </div>
</div>
<script type="text/javascript" src="/cl/js/jquery-1.7.2.min.js?_=171"></script>
<script type="text/javascript" src="/cl/js/jquery-ui-1.8.21.custom.min.js?_=171"></script>
<script type="text/javascript" src="/cl/js/pluging/jquery.blockUI.min.js?_=171"></script>
<!-- <script type="text/javascript" src="/cl/js/common.js?_=171"></script> -->
<script type="text/javascript" src="/cl/js/pluging/jquery.cookie.js?_=171"></script>
<script type="text/javascript" src="/cl/index.js"></script>
<script type="text/javascript" language="javascript" src="/js/left_mouse.js"></script>
<script>showType();</script>