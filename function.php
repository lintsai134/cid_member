<?php
use XoopsModules\Tadtools\TreeTable;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\TadMod;

use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;

include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";//載入tadtools/tad_function.php

$TadMod = new TadMod(basename(__DIR__));
// $TadMod->add_menu('前台選項', 'index.php?op=create', true);

function lin_member_form($mb_sn = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//套用formValidator驗證機制
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
	  redirect_header("main.php", 3, _TAD_NEED_TADTOOLS);
	}
	include_once TADTOOLS_PATH."/formValidator.php";
	$formValidator      = new formValidator("#myForm", true);
	$formValidator_code = $formValidator->render();
	$xoopsTpl->assign("formValidator_code",$formValidator_code);	
	
    //include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");
	$mb_sn = $_GET['mb_sn'];
    //抓取預設值
    if (!empty($mb_sn)) {
        $DBV = get_lin_member($mb_sn);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「mb_sn」欄位預設值
    $mb_sn = !isset($DBV['mb_sn']) ? '' : $DBV['mb_sn'];
    $xoopsTpl->assign('mb_sn', $mb_sn);

    //設定「cate_sn」欄位預設值
    $cate_sn = !isset($DBV['cate_sn']) ? '' : $DBV['cate_sn'];
    $xoopsTpl->assign('cate_sn', $cate_sn);

    //設定「mb_com」欄位預設值
    $mb_com = !isset($DBV['mb_com']) ? '' : $DBV['mb_com'];
    $xoopsTpl->assign('mb_com', $mb_com);

    //設定「mb_name」欄位預設值
    $mb_name = !isset($DBV['mb_name']) ? '' : $DBV['mb_name'];
    $xoopsTpl->assign('mb_name', $mb_name);

    //設定「mb_mobile」欄位預設值
    $mb_mobile = !isset($DBV['mb_mobile']) ? '' : $DBV['mb_mobile'];
    $xoopsTpl->assign('mb_mobile', $mb_mobile);

    //設定「mb_phone」欄位預設值
    $mb_phone = !isset($DBV['mb_phone']) ? '' : $DBV['mb_phone'];
    $xoopsTpl->assign('mb_phone', $mb_phone);

    //設定「mb_fax」欄位預設值
    $mb_fax = !isset($DBV['mb_fax']) ? '' : $DBV['mb_fax'];
    $xoopsTpl->assign('mb_fax', $mb_fax);

    //設定「mb_email」欄位預設值
    $mb_email = !isset($DBV['mb_email']) ? '' : $DBV['mb_email'];
    $xoopsTpl->assign('mb_email', $mb_email);

    //設定「mb_url」欄位預設值
    $mb_url = !isset($DBV['mb_url']) ? '' : $DBV['mb_url'];
    $xoopsTpl->assign('mb_url', $mb_url);

    //設定「mb_location」欄位預設值
    $mb_location = !isset($DBV['mb_location']) ? '' : $DBV['mb_location'];
    $xoopsTpl->assign('mb_location', $mb_location);

    //設定「uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $uid = (!isset($DBV['uid'])) ? '' : $DBV['uid'];
    $xoopsTpl->assign('mb_uid', $mb_uid);

    //設定「mb_last_update」欄位預設值
    $mb_last_update = !isset($DBV['mb_last_update']) ? date('Y-m-d H:i:s') : $DBV['mb_last_update'];
    $xoopsTpl->assign('mb_last_update', $mb_last_update);

    //設定「mb_memo」欄位預設值
    $mb_memo = !isset($DBV['mb_memo']) ? '' : $DBV['mb_memo'];
    $xoopsTpl->assign('mb_memo', $mb_memo);

    $op = (empty($mb_sn)) ? 'insert_lin_member' : 'update_lin_member';

/*	$FormValidator = new FormValidator('#myForm', true);
	$FormValidator->render();
//die("取得：{$mb_sn}有嗎？");

    //評鑑說明
    $ck = new CkEditor('lin_member', 'mb_name', $mb_name);
    $ck->setHeight(100);
    $editor = $ck->render();
*/

//會員類別選擇(如果讓【會員】自行變更內容，這個項目要設定隱藏)
	$selected=($cate_sn==0)?" selected=selected":"";
	$stop_level=1;
	$select_cate_sn="
	類別：<select name='cate_sn' size=1>
		".get_cate_option($cate_sn,1,0)."
	</select> 
	";
	if(empty($mb_sn)){
		$Form_title="【新增會員資料】";
	}else{
		$Form_title="【編輯會員資料】";
	}

	$main = "
	<div style='margin:0em 4em; '>
	<form action='?op=show&mb_sn={$mb_sn}' method='post' id='myForm' enctype='multipart/form-data'>
		<div style='width:100%; color: #2F0000; font-size:0.5cm; background-color:#FFF8D7; text-shadow: 0.1em 0.1em 0.2em black'>
		<div class='btn btn-success disabled btn-block'>$Form_title</div><p>
		$select_cate_sn<p>
		名稱：<input type='text' name='mb_com' size='25' value='{$mb_com}' id='mb_com' class='validate[required , min[1], max[100]]'><p>
		姓名：<input type='text' name='mb_name' size='10' value='{$mb_name}' id='mb_name' class='validate[required , min[1], max[50]]'><p>
		行動：<input type='text' name='mb_mobile' size='20' value='{$mb_mobile}' id='mb_mobile' ><p>
		電話：<input type='text' name='mb_phone' size='20' value='{$mb_phone}' id='mb_phone' ><p>
		傳真：<input type='text' name='mb_fax' size='20' value='{$mb_fax}' id='mb_fax' ><p>
		信箱：<input type='text' name='mb_email' size='25' value='{$mb_email}' id='mb_email' ><p>
		地址：<input type='text' name='mb_location' size='25' value='{$mb_location}' id='mb_location' ><p>
		網址：<input type='text' name='mb_url' size='25' value='{$mb_url}' id='mb_url' '><p>
		<div class='btn btn-info disabled btn-block'>會員簡介</div>
		<div >
		<textarea name='mb_memo' style='width:100%;height:200px; font-size:0.5cm;' id='mb_memo'>{$mb_memo}</textarea>
		</div>
		</div>
	<tr><th colspan='2'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='hidden' name='uid' value='{$uid}'>
	<input type='hidden' name='mb_sn' value='{$mb_sn}'>
	<center>
		<input class='btn btn-warning' type='submit' value='存檔' />
		<input class='btn btn-default' type='button' onclick=\"window.location.replace('?op=show&mb_sn={$mb_sn}')\" value='取消' /> 
	</center>
	</th></tr>
	</form>
	</div>";
		
//以下會產生這些變數： $mb_sn ,$cate_sn ,$mb_com ,$mb_name ,$mb_mobile ,$mb_phone ,$mb_fax ,$mb_email ,$mb_url ,$mb_location ,$mb_last_update ,$mb_memo 
/*資料不傳過去模組樣板
    $xoopsTpl->assign('select_cate_sn', $select_cate_sn);
    $xoopsTpl->assign('Form_title', $Form_title);
    $xoopsTpl->assign('mb_sn', $mb_sn);
    $xoopsTpl->assign('mb_com', $mb_com);
    $xoopsTpl->assign('mb_name', $mb_name);
    $xoopsTpl->assign('mb_mobile', $mb_mobile);
    $xoopsTpl->assign('mb_phone', $mb_phone);
    $xoopsTpl->assign('mb_fax', $mb_fax);
    $xoopsTpl->assign('mb_email', $mb_email);
    $xoopsTpl->assign('mb_location', $mb_location);
    $xoopsTpl->assign('mb_url', $mb_url);
    $xoopsTpl->assign('op', 'lin_member_form');
    $xoopsTpl->assign('uid', $uid);
*/
//	$main=ugm_div($Form_title,$main,"shadow");
//die("值：".$mb_sn."，姓名：".$mb_name."，op：".$op);
	echo $main;
}

//新增資料到lin_member中
function insert_lin_member()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
    //取得最後新增資料的流水編號

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $myts = \MyTextSanitizer::getInstance();
    $_POST['cate_sn'] = $myts->addSlashes($_POST['cate_sn']);
    $_POST['mb_com'] = $myts->addSlashes($_POST['mb_com']);
    $_POST['mb_name'] = $myts->addSlashes($_POST['mb_name']);
    $_POST['mb_mobile'] = $myts->addSlashes($_POST['mb_mobile']);
    $_POST['mb_phone'] = $myts->addSlashes($_POST['mb_phone']);
    $_POST['mb_fax'] = $myts->addSlashes($_POST['mb_fax']);
    $_POST['mb_email'] = $myts->addSlashes($_POST['mb_email']);
    $_POST['mb_url'] = $myts->addSlashes($_POST['mb_url']);
    $_POST['mb_location'] = $myts->addSlashes($_POST['mb_location']);
    $_POST['mb_memo'] = $myts->addSlashes($_POST['mb_memo']);

    $sql = 'insert into `' . $xoopsDB->prefix('lin_member') . "`
  (`cate_sn` , `mb_com` , `mb_name` , `mb_mobile` , `mb_phone` , `mb_fax` , `mb_email` , `mb_url` , `mb_location` , `mb_last_update` , `mb_memo`)
  values('{$_POST['cate_sn']}' , '{$_POST['mb_com']}' , '{$_POST['mb_name']}' , '{$_POST['mb_mobile']}' , '{$_POST['mb_phone']}' , '{$_POST['mb_fax']}' , '{$_POST['mb_email']}' , '{$_POST['mb_url']}' , '{$_POST['mb_location']}' , '" . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . "' , '{$_POST['mb_memo']}')";
 
	$xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $mb_sn = $xoopsDB->getInsertId();

//資料庫的變數： $mb_sn ,$cate_sn ,$mb_com ,$mb_name ,$mb_mobile ,$mb_phone ,$mb_fax ,$mb_email ,$mb_url ,$mb_location ,$mb_last_update ,$mb_memo 


//    $_POST['mb_com'] = change_charset($_POST['mb_com'], false);

//    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['mb_com']}");

    return $mb_sn;
}

//更新lin_member某一筆資料
function update_lin_member($mb_sn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
	
//    $mb = get_lin_member($mb_sn);
    //取得使用者編號
//    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
//
    $myts = \MyTextSanitizer::getInstance();
	$mb_sn = $myts->addSlashes($_POST['mb_sn']);
    $_POST['cate_sn'] = $myts->addSlashes($_POST['cate_sn']);
    $_POST['mb_com'] = $myts->addSlashes($_POST['mb_com']);
    $_POST['mb_name'] = $myts->addSlashes($_POST['mb_name']);
    $_POST['mb_mobile'] = $myts->addSlashes($_POST['mb_mobile']);
    $_POST['mb_phone'] = $myts->addSlashes($_POST['mb_phone']);
    $_POST['mb_fax'] = $myts->addSlashes($_POST['mb_fax']);
    $_POST['mb_email'] = $myts->addSlashes($_POST['mb_email']);
    $_POST['mb_url'] = $myts->addSlashes($_POST['mb_url']);
    $_POST['mb_location'] = $myts->addSlashes($_POST['mb_location']);
    $_POST['mb_memo'] = $myts->addSlashes($_POST['mb_memo']);
//die("值：".$mb_sn."，測試：".$_POST['mb_com']);

    $sql = 'update `' . $xoopsDB->prefix('lin_member') . "` set
    `cate_sn` = '{$_POST['cate_sn']}' ,
    `mb_com` = '{$_POST['mb_com']}' ,
    `mb_name` = '{$_POST['mb_name']}' ,
    `mb_mobile` = '{$_POST['mb_mobile']}' ,
    `mb_phone` = '{$_POST['mb_phone']}' ,
    `mb_fax` = '{$_POST['mb_fax']}' ,
    `mb_email` = '{$_POST['mb_email']}' ,
    `mb_url` = '{$_POST['mb_url']}' ,
    `mb_location` = '{$_POST['mb_location']}' ,
    `mb_memo` = '{$_POST['mb_memo']}' ,
    `mb_last_update` = '" . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . "'
  where `mb_sn` = '$mb_sn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
	

//    $_POST['mb_com'] = change_charset($_POST['mb_com'], false);
//    $mb['mb_com'] = change_charset($mb['mb_com'], false);

/*    if (is_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['mb_com']}")) {
        if ($mb['mb_com'] != $_POST['mb_com']) {
            rename(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['mb_com']}", XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['mb_com']}");
        }
    } else {
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['mb_com']}");
    }
*/
    header("location: {$_SERVER['PHP_SELF']}?op=show&mb_sn={$mb_sn}");
//    return $mb_sn;

}

//列表--區塊使用
function show_blocks_test()
{
    global $xoopsDB, $xoopsTpl, $isAdmin,$xoopsModule,$isAdminMember,$xoopsModuleConfig,$xoopsUser;
	$get_cate = $_GET['cate_sn'];
	if($get_cate==1){
		$bg_color='1, 6, 88, 1';
	}elseif($get_cate==2){
		$bg_color='16, 88, 8, 1';
	}elseif($get_cate==3){
		$bg_color='86, 1, 6, 8';
	}else{
	}
	$main = "
<script src='https://libs.baidu.com/jquery/1.10.2/jquery.min.js'></script>
<style>

</style>
<div class='gundong'>
    <ul>
	";	
	
	$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn`= '$get_cate'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$nums=$xoopsDB->getRowsNum($result);//目前執行結果的資料數量
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
		foreach ($all as $k => $v) {
			$$k = $v;
		}
		$name_t=$mb_com."－".$mb_name;//要顯示的文字
		if(mb_strlen($name_t)>12){
			$name_t=mb_substr( $name_t,0,11,"utf-8")."...";
		}
		$main.="
		<li>
		<a target='_parent' href='/modules/member/index.php?op=show&mb_sn={$mb_sn}' >
		<div style='border-width: 1px ; text-align:left; height: 40px ; padding: 0px; background-color: rgba($bg_color) ;border-radius: 4px;'>
		<span style ='color:rgb(255, 255, 255);'>
		{$name_t}</ span></ div>
		</a><p>
		</li>
		";
	}
	$main.="
        </ul>
</div>
<script>

</script>
	";
echo $main;	
		
}

//列表--區塊使用
function show_blocks()
{
    global $xoopsDB, $xoopsTpl, $isAdmin,$xoopsModule,$isAdminMember,$xoopsModuleConfig,$xoopsUser;
	$get_cate = $_GET['cate_sn'];
	if($get_cate==1){
		$bg_color='1, 6, 88, 1';
	}elseif($get_cate==2){
		$bg_color='16, 88, 8, 1';
	}elseif($get_cate==3){
		$bg_color='86, 1, 6, 8';
	}else{
	}
	$main = "
<script src='https://libs.baidu.com/jquery/1.10.2/jquery.min.js'></script>
<style>
* {
	margin:0;
	padding:0;
}
html,body {
	height:100%;
	overflow:hidden;
}
#con-countDown {
	color:#fff;
	font-size:0.64rem;
}
#con-countDown span {
	display:inline-block;
	margin-right:0.1rem;
}
.gundong {
	height:200px;
	overflow:hidden;
	width:220px;
	margin: 0 auto;
	position:relative;
}
.gundong ul {
	position:absolute;
	top:0;
	left:0;
	width:100%;
}
.gundong li {
	height:50px;
	line-height:40px;
	color:#ccc;
	font-size:18px;
}
.gundong li.active {
	color:#fff
}
</style>
<div class='gundong'>
    <ul>
	";	
	
	$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn`= '$get_cate'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$nums=$xoopsDB->getRowsNum($result);//目前執行結果的資料數量
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
		foreach ($all as $k => $v) {
			$$k = $v;
		}
		$name_t=$mb_com."－".$mb_name;//要顯示的文字
		if(mb_strlen($name_t)>12){
			$name_t=mb_substr( $name_t,0,11,"utf-8")."...";
		}
		$main.="
		<li>
		<a target='_parent' href='/modules/member/index.php?op=show&mb_sn={$mb_sn}' >
		<div style='border-width: 1px ; text-align:left; height: 40px ; padding: 0px; background-color: rgba($bg_color) ;border-radius: 4px;'>
		<span style ='color:rgb(255, 255, 255);'>
		{$name_t}</ span></ div>
		</a><p>
		</li>
		";
	}
	$main.="
        </ul>
</div>
<script>
	function shipei() {
	 //适配
	 if (innerWidth > 1920) {
		 document.documentElement.style.fontSize = '100px';
	 } else {
		 document.documentElement.style.fontSize = 100 * (innerWidth / 1920) + 'px';
	 }
	 if (innerWidth < 751) {
		 document.documentElement.style.fontSize = 100 * (innerWidth / 750) + 'px';
	 }
	};
	 shipei();

	// JavaScript Document
	var liHeiight = $('.gundong li').height();
	var num = $('.gundong li').size()
	if (num < 6) {
	 $('.gundong').css({
		 'height': liHeiight * (num - 1)
	 });
	};

	function gundong(val) {
	 var clone = $('.gundong li').first().clone();
	 $('.gundong ul').append(clone);
	 $('.gundong li').removeClass('active');
	 $('.gundong ul').stop().animate({
		 top: -liHeiight
	 }, 800, function() {
		 $('.gundong ul').css({
			 top: 0
		 });
		 $('.gundong li').eq(0).remove();
		 $('.gundong li').eq(0).addClass('active');
	 })
	};
	var t = setInterval(function() {
	 gundong();
	}, 2000);
</script>
	";
echo $main;	
		
}

//列表--區塊使用
function show_blocks_old()
{
    global $xoopsDB, $xoopsTpl, $isAdmin,$xoopsModule,$isAdminMember,$xoopsModuleConfig,$xoopsUser;
	$get_cate = $_GET['cate_sn'];
	if($get_cate==1){
		$bg_color='1, 6, 88, 1';
	}elseif($get_cate==2){
		$bg_color='16, 88, 8, 1';
	}elseif($get_cate==3){
		$bg_color='86, 1, 6, 8';
	}else{
	}
	$main = "
	<!doctype html>
	<html>
	<head>
	<meta charset='utf-8'>
	<title>純Css文字輪播輪播-jq22.com</title>
	</head>
	<body>
	<div class='div1'>
		<div class='div2'>
	";	
	
	$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn`= '$get_cate'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$nums=$xoopsDB->getRowsNum($result);//目前執行結果的資料數量
	$h_nums=$nums*60;
	$i=1;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
		foreach ($all as $k => $v) {
			$$k = $v;
		}
		
		$word_o = mb_strlen($mb_com)+mb_strlen($mb_name);//列表長度
		if($word_o < 10){
			$height_no = '20px';
		}elseif($word_o > 18){
			$height_no = '60px';
		}else{
			$height_no = '40px';
		}
		$main.="
		<P>
		<center>$i / $nums
		<a target='_parent' href='/modules/member/index.php?op=show&mb_sn={$mb_sn}' >
		<div style='border-width: 1px ; width: 150px; text-align:left; height: $height_no ; padding: 0px; background-color: rgba($bg_color) ;border-radius: 4px;'>
		<span style ='color:rgb(255, 255, 255);'>
		{$mb_com}</ span>-{$mb_name}</ div>
		</a><br>
		</P>
		";
		$i++;
	}
	$main.="
			</div>
	</div>

	<script src='http://libs.baidu.com/jquery/1.11.3/jquery.min.js'></script>
	<style>
	.div1 {
		width:200px;
		height:".$h_nums."px;
		overflow:hidden;
		margin:auto;
		position:relative;
	}
	@keyframes anis {
		100% {
		transform:translateY(-500px)
	}
	}img {
		position:absolute;
	}
	.div2 {
		animation:anis 10s linear infinite;
	}
	.div2:hover {
		animation-play-state:paused;
	}
	</style>

	</body>
	</html>	

	";
echo $main;	
		
}

//列表--顯示預設頁面內容
function show_content($located)
{
    global $xoopsDB, $xoopsTpl, $isAdmin,$xoopsModule,$isAdminMember,$xoopsModuleConfig,$xoopsUser;
	$DIRNAME=$xoopsModule->getVar('dirname');
	$and_key="";
	$get_cate = $_GET['cate_sn'];

	//網站判斷
		if($_GET['url_no']==0){
			$url_no = 0;
			$url_bg0 = '#B9B973';
			$url_bg1=$url_bg2 = '#FFF';
			//$url_tl =  '目前無查詢是否有網站，按此搜尋有網站會員';
		}elseif($_GET['url_no']==1){
			$url_no = 1;
			$url_bg1 = '#B9B973';
			$url_bg0=$url_bg2 = '#FFF';
			//$url_tl =  '目前顯示為有網站會員，按此搜尋無網站會員';
		}else{//($_GET['url_no']==2)
			$url_no = 2;
			$url_bg2 = '#B9B973';
			$url_bg1=$url_bg0 = '#FFF';
			//$url_tl =  '目前顯示為無網站會員，按此無查詢是否有網站';
		}
	
	//搜尋
	if(isset($_GET['member_key'])){
	  $member_key=SqlFilter($_GET['member_key'],"trim,addslashes,strip_tags");
	  $and_key=empty($member_key)?"":" where mb_com like '%{$member_key}%' or mb_name like '%{$member_key}%' or mb_location like '%{$member_key}%' or mb_phone like '%{$member_key}%' or mb_mobile like '%{$member_key}%' or mb_fax like '%{$member_key}%' or mb_url like '%{$member_key}%' or mb_memo like '%{$member_key}%'";
	  }
	if($url_no == ''){
		$url_key="";
	}elseif($url_no == 1){
		$url_key=" where not`mb_url`= ''";
//die("是否有來這邊{$url_key}！");
	}elseif($url_no == 2){
		$url_key=" where `mb_url`= ''";
	}else{
		$url_key="";
	}

	if($get_cate==0 or !empty($and_key)){
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." {$and_key}{$url_key}";
	  }elseif($url_no==1 and $get_cate==''){
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." {$url_key}";
	  }else{
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn`= '$get_cate'";
	  } 
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	$cate = get_cate_array();//讀取類別

  if($xoopsUser){//管理員使用
	if($xoopsUser->isAdmin ()){
		//新增會員按鈕
	$add_button= "
	<div style='float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?op=lin_member_form' class='btn btn-primary'>
	【新增】
	</a>
	</div>
	<div style='background-color:#fff; clear:left;'>
	</div>
	
	";
		//查詢網站選項
	$url_button="
		<select onChange='location.href=\"{$_SERVER['PHP_SELF']}?url_no=\"+this.value'>
		<option value='' >【網站查詢】</option>
		<option value='0' >【無查詢】</option> 
		<option value='1' >【有網站】</option>
		<option value='2' >【無網站】</option>
		</select> 
	";
	}}
	
//echo $_GET['cate_sn'];
	$selected=($cate_sn==0)?" selected=selected":"";
	$stop_level=1;
	$select_cate_sn="
	<select name='cate_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?cate_sn=\"+this.value'>
		<option value='0' {$selected}> 全部 </option>".get_cate_option($get_cate,$stop_level,$level)."
	</select> 
	";
	$search_all="
	<div id='member_search'>
		<form method='get' action='{$form_action}'>
			<input type='text' name='member_key'  size='10' value='' />
			<input type='hidden' name='op' value='search' />
			<input type='submit' value='會員搜尋' />
		</form>
	</div>
	";

//後台：$located==1；前台：$located==2
//是否可以區分在前後台顯示，$locat==(僅後台：1)；{(僅前台：2)；(全顯：0)；(隱藏：9)}
	$url_locat=1;//<--修改此數值，可控制查詢網站【按鈕】於前後台顯示、隱藏
	//$data_locat=1;
	
    $i = 1;
//	die($post_n);
 	$main.="
	<table border='0' cellspacing='0' cellpadding='0' >
	<tr>
	<td>{$select_cate_sn}".show_locat(1,$located,$url_button)."</td>
	<td>".show_locat(1,$located,$add_button)."</td>
	<td align='right'>{$search_all}</td>
	</tr>
	</table>
	<div class='table-responsive'>
	<table border='1' cellspacing='0' cellpadding='0' class='table'>
		<tr>
			<td bgcolor='#ffff00' width=6%>
			<center>序號</center>
			</td>
			<td bgcolor='#ffff00' width=10%>
			類別
			</td>
			<td bgcolor='#ffff00' width=46%>
			名稱
			</td>
			<td bgcolor='#ffff00' width=14%>
			姓名
			</td>
			<td bgcolor='#ffff00' width=14%>
			電話
			</td>";
	if($located==1){//如果是後台才執行，前台為2
		$main.="
			<td bgcolor='#ffff00'>
			手機
			</td>
			<td bgcolor='#ffff00'>
			傳真
			</td>
			<td bgcolor='#ffff00'>
			電郵
			</td>
			<td bgcolor='#ffff00'>
			網站
			</td>
			<td bgcolor='#ffff00'>
			簡介
			</td>
		";
	}
 	$main.="
		</tr>
	";
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $mb_sn ,$cate_sn ,$mb_com ,$mb_name ,$mb_mobile ,$mb_phone ,$mb_fax ,$mb_email ,$mb_url ,$mb_location ,$mb_last_update ,$mb_memo 

        foreach ($all as $k => $v) {
            $$k = $v;
        }
	if($i%2==0){
		$color_n="#ECECFF";
	}else{
		$color_n="#FFE6D9";
	}
	$cate_name = $cate[$cate_sn];
	$module_name=$xoopsModule->getVar('dirname');
	
	if($mb_mobile==''){$mobile_y='';}else{$mobile_y='Yes';}
	if($mb_fax==''){$fax_y='';}else{$fax_y='Yes';}
	if($mb_email==''){$email_y="<img src='/modules/$module_name/images/noemail.png'>";}else{$email_y="<img src='/modules/$module_name/images/email.png' title='$email'>";}
	if($mb_url==''){$url_y='';}else{$url_y="<img src='/modules/$module_name/images/url.png' title='$url'>";}
	if($mb_memo==''){$memo_y='';}else{$memo_y='Yes';}
	
	$main.="
		<tr style='background-color:$color_n;'>
			<td style='background-color:$color_n;'>
			<center>
			<a href='{$_SERVER['PHP_SELF']}?op=show&mb_sn={$mb_sn}'>
				$i 
			</a>
			</center>
			</td>
			<td>
			{$cate_name}
			</td>
			<td>
			<a href='{$_SERVER['PHP_SELF']}?op=show&mb_sn={$mb_sn}'>
				{$mb_com}
			</a>
			</td>
			<td>
			{$mb_name}
			</td>
			<td>
			{$mb_phone}
			</td>";
	if($located==1){//如果是後台才執行，前台為2
		$main.="
			<td>{$mobile_y}
			</td>
			<td>{$fax_y}
			</td>
			<td>{$email_y}
			</td>
			<td><a href={$mb_url} target='blank'>{$url_y}</a>
			</td>
			<td>{$memo_y}
			</td>
		";
	}
 	$main.="
		</tr>
";
		$i++;
	}
	$main.="
	</table>
	</div>";

echo $main;
//    $xoopsTpl->assign('content', $main);
}

//查詢網站判斷

function show_locat($locat,$located,$add_button){
	
	if($locat==1){//$locat==(僅後台：1)；{(僅前台：2)；(全顯：0)；(隱藏：)}
		if($located==1){return $add_button;}
	}elseif($locat==2){
		if($located==2){return $add_button;}
//die("測試$add_button");
	}elseif($locat==0){
		if($located==1 or $located==2){return $add_button;}
	}else{
		if($located==1 and $located==2){return $add_button;}
	}
}

//秀出單一個別資料
function show($located){
	global $xoopsDB,$xoopsModule,$xoopsUser, $xoopsTpl;
	$DIRNAME=$xoopsModule->getVar('dirname');
	
    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

	$op="show";
	$mb_sn = $_GET['mb_sn'];
	$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `mb_sn`='$mb_sn'";
	//以下會產生這些變數： $mb_sn ,$cate_sn ,$mb_com ,$mb_name ,$mb_mobile ,$mb_phone ,$mb_fax ,$mb_email ,$mb_url ,$mb_location ,$mb_last_update ,$mb_memo 

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);

	foreach($all as $k=>$v){
	  $$k=$v;
	}
	$mb_memo=nl2br($mb_memo);
	$cate = get_cate_array();//讀取類別
	$cate_name = $cate[$cate_sn];

	//縮排、載入JS
//	<--! sweetalert彈出操作框套件JS部分-->

	//判斷空值，回列表
	if (empty($mb_com)){
		show_content($located);
		return;
	}
	
	//模組路徑 {$xoops_url}
	if($xoopsUser){//管理員使用
		if($xoopsUser->isAdmin ()){
		$admin= "
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form' class='btn btn-primary'>【新增】</a>
		</div>
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form&mb_sn={$mb_sn}' class='btn btn-warning'>【編輯】</a>
		</div>
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=delete&mb_sn={$mb_sn}' class='btn btn-danger'>【刪除】</a>
		</div>
		";
		}}
    $show_locat=show_locat(0,$located,$admin);//第一個項目，$locat==(僅後台：1)；{(僅前台：2)；(全顯：0)；(隱藏：其他數字)}
/*	
    $xoopsTpl->assign('cate_sn', $cate_sn);
    $xoopsTpl->assign('cate_name', $cate_name);
    $xoopsTpl->assign('mb_sn', $mb_sn);
    $xoopsTpl->assign('mb_com', $mb_com);
    $xoopsTpl->assign('mb_name', $mb_name);
    $xoopsTpl->assign('mb_mobile', $mb_mobile);
    $xoopsTpl->assign('mb_phone', $mb_phone);
    $xoopsTpl->assign('mb_fax', $mb_fax);
    $xoopsTpl->assign('mb_email', $mb_email);
    $xoopsTpl->assign('mb_location', $mb_location);
    $xoopsTpl->assign('mb_url', $mb_url);
    $xoopsTpl->assign('show_locat', $show_locat);
    $xoopsTpl->assign('mb_memo', $mb_memo);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('isAdmin', $isAdmin);
//    $xoopsTpl->assign('', $);
*/	
	$main ="
		{$show_locat}
		<div style='float:left;'>
		<a href='?cate_sn={$cate_sn}' class='btn btn-success'>【列表】</a>
		</div>
		<div style='background-color:#fff; clear:left;'>
		</div>
		<div style='background-color:#000;color:yellow;' class='disabled'>
			【{$cate_name}】
			<span style='color:#fff; font-size:0.5cm; font-weight:bold;' class='disabled' >{$mb_com}</span>
		</div><p>
		<div class='btn-default'>
		<span class='disabled'>姓名：{$mb_name}</span><p><p>
		";
		if(!empty($mb_mobile)){
			$main.="<span class='disabled'>行動：{$mb_mobile}</span><p>";
		}
		if(!empty($mb_phone)){
			$main.="<span class='disabled'>電話：{$mb_phone}</span><p>";
		}
		if(!empty($mb_fax)){
			$main.="<span class='disabled'>傳真：{$mb_fax}</span><p>";
		}
		if(!empty($mb_email)){
			$main.="<span class='disabled'>信箱：<a href='mailto:{$mb_email}'>{$mb_email}</a></span><p>";
		}
		if(!empty($mb_location)){
			$main.="<span class='disabled'>地址：<a href='http://maps.google.com.tw/maps?f=q&hl=zh-TW&geocode=&q={$mb_location}' target=_blank title='連結google地圖'>{$mb_location}</a></span><p>";
		}
		if(!empty($mb_url)){
			$main.="<span class='disabled'>網址：<a href='{$mb_url}' target='blank'>{$mb_url}</a></span>";
		}
	$main.="</div>";
		if(!empty($mb_memo)){
			$main.="<div style='background-color:#006000;' class='btn-success'>
				<span style='color:#fff; font-weight:bold;'>會員簡介</span>
			</div>
			<div style='background-color:#F3F3FA;'><p>{$mb_memo}</p>
			</div>";
		}
	echo $main;
}

//刪除lin_member某筆資料資料
function delete_lin_member()
{
	global $xoopsDB, $xoopsTpl;
	$mb_sn = $_GET['mb_sn'];
    if (empty($mb_sn)) {
//		die("沒選項");
        return;
    }
		$question="
		<script>
		if ( confirm ('您確定【刪除】資料嗎？')  )  
			location.href='main.php?op=deling&mb_sn=$mb_sn';  
			else location.href='{$_SERVER['PHP_SELF']}?op=show&mb_sn={$mb_sn}';
		</script>
		";
		
		die($question);
		//echo $question;//此行無法執行
}

//確定刪除lin_member某筆資料資料
function deleteing_lin_member()
{
	global $xoopsDB;
	$mb_sn = $_GET['mb_sn'];
    if (empty($mb_sn)) {
        return;
    }
//die("<button id='demo1'>Demo {$mb_sn}</button>");
	$sql = 'delete from ' . $xoopsDB->prefix('lin_member') . " where mb_sn = '{$mb_sn}'";
	$xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

}

//取得類別名稱陣列
function get_cate_array()
{
    global $xoopsDB;
    $sql = 'select cate_sn,cate_title from ' . $xoopsDB->prefix('lin_mb_cate') . '';
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($cate_sn, $cate_title) = $xoopsDB->fetchRow($result)) {
        $arr[$cate_sn] = $cate_title;
    }

    return $arr;
}

//以流水號取得某筆lin_member資料
function get_lin_member($mb_sn = '')
{
    global $xoopsDB;
    if (empty($mb_sn)) {
        return;
    }

    $sql = 'select * from `' . $xoopsDB->prefix('lin_member') . "` where `mb_sn` = '{$mb_sn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

#-- 取得選單選項get_menu_option
function get_cate_option($cate_sn_chk=0,$stop_level=1,$level=0){
  global $xoopsDB;
  
  if($level>=$stop_level)return;
  $level++;
  
  $sql = "select `cate_sn`,`cate_title` from ".$xoopsDB->prefix("lin_mb_cate")."  order by `cate_sn`";
  
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=1;
  $level_mark="";
  while ($level-$i>0){
    $level_mark.="&nbsp;&nbsp;&nbsp;";
    $i++;
  }
  while($all=$xoopsDB->fetchArray($result) ){
  //以下會產生這些變數： 【$msn,$title】
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $selected=($cate_sn==$cate_sn_chk)?" selected=selected":"";
    $main.="<option value={$cate_sn}{$selected}>{$level_mark}{$cate_title}</option>";
    //$main.=$head_text.$class_sn.$selected.$body_text.$class_name.$foot_text;
    $main.=get_cate_option($cate_sn_chk,$stop_level,$level);
//echo $cate_sn;	
  }
  return $main;
}

###############################################################################
#  資料過瀘
#
#  SqlFilter($Variable='',"trim,addslashes,strip_tags,htmlspecialchars,intval")
###############################################################################
function SqlFilter($Variable='',$method='text'){
  if(empty($Variable))return '';
  $methods = explode(",", $method);
  foreach($methods as $method){
    switch($method){
  	//去除前後空白
    case "trim":
  	  $Variable=trim($Variable);
  	break;
  	//特殊字符轉義
  	case "addslashes":
  	  $Variable=(! get_magic_quotes_gpc()) ? addslashes($Variable) : $Variable;
  	break;
  	//去除html、php標籤
  	case "strip_tags":
  	  $Variable=strip_tags($Variable);
  	break;
  	//轉換特殊字元成為HTML實體
  	case "htmlspecialchars":
  	  $Variable=htmlspecialchars($Variable);
  	break;
    case "intval":
  	  $Variable=intval($Variable); 
  	break;
  	default:
  	break;
    }
  }
  return  $Variable ;
}

/*********************UGM自訂函數 **************************/
###############################################################################
#  ugm_div($data,$corners)
#  圓角
###############################################################################
function ugm_div($title="",$data="",$corners=""){

  if($corners=="shadow"){
    $title=empty($title)?"":"<div class='Block1Header'><h1>{$title}</h1></div>";
    $main="
      <div class='Block1Border'><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1'>{$title}
            <div class='Block1ContentBorder'>{$data}
            </div>
        </div></div>
    ";
  }else{
    $main="<div class='BlockBorder'><div class='BlockBL'><div></div></div><div class='BlockBR'><div></div></div><div class='BlockTL'></div><div class='BlockTR'><div></div></div><div class='BlockT'></div><div class='BlockR'><div></div></div><div class='BlockB'><div></div></div><div class='BlockL'></div><div class='BlockC'></div><div class='Block'>\n
    {$data}\n
    </div></div>\n";
   $main=empty($title)? $main:"<span class='title'>{$title}</span>".$main; 
    
  }
  
  return $main;
}
