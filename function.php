<?php
use XoopsModules\Tadtools\TadMod;
$TadMod = new TadMod(basename(__DIR__));
// $TadMod->add_menu('前台選項', 'index.php?op=create', true);

//新增資料
function create()
{
	$get_mb = $_GET['mb_sn'];
	
	$main="
		新增{$get_mb}資料
	";	
	echo $main;
}

//修改資料
function edit()
{
	$get_mb = $_GET['mb_sn'];

	$main="
		修改{$get_mb}資料
	";	
	echo $main;
}

//刪除資料
function del()
{
	$get_mb = $_GET['mb_sn'];

	$main="
		刪除{$get_mb}資料
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
	}elseif($url_no == 2){
		$url_key=" where `mb_url`= ''";
	}else{
		$url_key="";
	}
//die("是否有來這邊{$url_key}！");

	if($get_cate==0 or !empty($and_key)){
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." {$and_key}";
	  }elseif($url_no==1 and $get_cate==''){
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." {$url_key}";
	  }else{
		$sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn`= '$get_cate'";
	  } 
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	$cate = get_cate_array();//讀取類別

  if($xoopsUser){//管理員使用
	if($xoopsUser->isAdmin ()){
	$admin= "
	<div style='border-color:#FFF; border-style:solid; background-color:#FF5151; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?op=insert_lin_member'>
	【新增】
	</a>
	</div>
	<div style='background-color:#fff; clear:left;'>
	</div>
	
	";
	$url_button="
	<div style='border-color:#000; border-style:solid; background-color:{$url_bg0}; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?url_no=0' title=$url_tl>
	【無查詢】
	</a>
	</div>
	<div style='border-color:#000; border-style:solid; background-color:{$url_bg1}; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?url_no=1' title=$url_tl>
	【有網站】
	</a>
	</div>
	<div style='border-color:#000; border-style:solid; background-color:{$url_bg2}; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?url_no=2' title=$url_tl>
	【無網站】
	</a>
	</div>

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
	<div id='ugm_member_search'>
		<form method='get' action='{$form_action}'>
			<input type='text' name='member_key'  size='10' value='' />
			<input type='hidden' name='op' value='search' />
			<input type='submit' value='會員搜尋' />
		</form>
	</div>
	";

//後台：$located==1；前台：located==2
//是否可以區分在前後台顯示，$locat==(僅後台：1)；{(僅前台：2)；(全顯：0)；(隱藏：9)}
	$url_locat=1;//<--修改此數值，可控制查詢網站【按鈕】於前後台顯示、隱藏
	//$data_locat=1;
	
    $i = 1;
//	die($post_n);
 	$main.="
	<table border='0' cellspacing='0' cellpadding='0' >
	<tr>
	<td>{$select_cate_sn}</td>
	<td>
	";
//查詢網站判斷
	if($url_locat==1){//$locat==(僅後台：1)；{(僅前台：2)；(全顯：0)；(隱藏：)}
		if($located==1){$main.="{$url_button}";}
	}elseif($url_locat==2){
		if($located==2){$main.="{$url_button}";}
	}elseif($url_locat==0){
		if($located==1 or $located==2){$main.="{$url_button}";}
	}else{
		if($located==1 and $located==2){$main.="{$url_button}";}
	}
 	$main.="
	{$admin}</td>
	<td align='right'>{$search_all}</td>
	</tr>
	</table>
	<table border='1' cellspacing='0' cellpadding='0' >
		<tr>
			<td bgcolor='#ffff00' width=7%>
			<center>序號</center>
			</td>
			<td bgcolor='#ffff00' width=10%>
			類別
			</td>
			<td bgcolor='#ffff00' width=45%>
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
			<center> $i </center>
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
	</table>";
	
//    $xoopsTpl->assign('content', $main);
echo $main;
}

//秀出單一個別資料
function show(){
	global $xoopsDB,$xoopsModule,$xoopsUser;
	$DIRNAME=$xoopsModule->getVar('dirname');
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

  if($xoopsUser){//管理員使用
	if($xoopsUser->isAdmin ()){
	$admin= "
	<div style='border-color:#FFF; border-style:solid; background-color:#FF5151; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?op=insert_lin_member'>
	【新增】
	</a>
	</div>
	<div style='border-color:#FFF; border-style:solid; background-color:#FFAF60; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?op=update_lin_member&mb_sn={$mb_sn}''>
	【修改】
	</a>
	</div>
	<div style='border-color:#FFF; border-style:solid; background-color:#1AFD9C; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?op=delete&mb_sn={$mb_sn}''>
	【刪除】
	</a>
	</div>
	";
	}}

	$main.="
	{$admin}
	<div style='border-color:#FFF; border-style:solid; background-color:#FFE66F; border-bottom:1 solid #000000; float:left;'>
	<a href='{$_SERVER['PHP_SELF']}?cate_sn={$cate_sn}'>【列表】</a>
	</div>
	<div style='background-color:#fff; clear:left;'>
	</div>
	<div style='background-color:#000;color:yellow;'>
		【{$cate_name}】
		<span style='color:#fff; font-weight:bold;'>{$mb_com}</span>
	</div>
	<div style=''>
	姓名：{$mb_name}<br>
	行動：{$mb_mobile}<br>
	電話：{$mb_phone}<br>
	傳真：{$mb_fax}<br>
	信箱：<a href='mailto:{$mb_email}'>{$mb_email}</a><br>
	地址：<a href='http://maps.google.com.tw/maps?f=q&hl=zh-TW&geocode=&q={$mb_location}' target=_blank title='連結google地圖'>{$mb_location}</a><br>
	網址：<a href={$mb_url} target='blank'>{$mb_url}</a>
	</div>
	<div style='background-color:#006000;'>
		<span style='color:#fff; font-weight:bold;'>會員簡介</span>
	</div>
	<div><p>{$mb_memo}</p>
	</div>
<!--先隱藏
	<div style='background-color:#2F0000;'>
		<span style='color:#fff; font-weight:bold;'>作品簡介</span>
	</div>
	<div>【作品暫時隱藏】		
	</div>
-->	
	";
	echo $main;
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
