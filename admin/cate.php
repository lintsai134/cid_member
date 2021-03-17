<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "adm_cate.tpl";
include_once "header.php";
include_once "../function.php";


/*-----------function區--------------*/
//類別列表
function list_cate(){
  global $xoopsDB,$xoopsModule,$xoopsConfig;
	$DIRNAME=$xoopsModule->getVar('dirname');
	$sql = "select * from ".$xoopsDB->prefix("lin_mb_cate");
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

/*	$i=0;  //建立$i值並針對迴圈+1。
	//資料
	$sql = "select * from " . $xoopsDB->prefix('lin_mb_cate') . " order by cate_sn DESC";
	$result = $xoopsDB -> query($sql) or die($sql) ;
	while(list($cate_sn,$cate_title) = $xoopsDB -> fetchRow($result)){
	$honor.="
	<tr  class='t{$i}'>
	<td>{$cate_title}</td>
	<td><a href='{$xoopsurl}/modules/member/admin/honoridsort.php?cate_sn={$cate_sn}'>編輯</a>|<a class='honordle' id='slowdivbotton{$i}' mane='cate_sn={$cate_sn}' href='#no'>刪除</a></td>
	</tr>
	";
	$i++;
	}
	//id='slowdivbotton{$i}' 會按照迴圈自動產生 id='slowdivbotton0',id='slowdivbotton1'等按鈕的id值.....  
*/	
	
	$main="
	<div class='table-responsive'>
		<a href='/modules/member/admin/cate.php?op=form_cate' class='btn btn-primary'>【新增】</a>
	<table border='1' cellspacing='0' cellpadding='0'  class='table'>
		<tr>
			<td bgcolor='#ffff00' width=10%>
			<center>編號</center>
			</td>
			<td bgcolor='#ffff00' width=35%>
			名稱
			</td>
			<td bgcolor='#ffff00' width=10%>
			功能
			</td>
			<td bgcolor='#ffff00' width=10%>
			排序
			</td>
			<td bgcolor='#ffff00' width=10%>
			是否啟用
			</td>
			<td bgcolor='#ffff00' width=25%>
			備註
			</td>
		</tr>
			";
	while (false !== ($all = $xoopsDB->fetchArray($result))) {
	//資料庫的變數： $cate_sn ,$cate_title ,$cate_sort ,$cate_enable ,$cate_memo 

	foreach ($all as $k => $v) {
		$$k = $v;
	}
		$main.="
		<tr>
			<td>
			<center>{$cate_sn}</center>
			</td>
			<td>
			{$cate_title}
			</td>
			<td>
			{$cate_func}
			</td>
			<td>
			{$cate_sort}
			</td>
			<td>
			{$cate_enable}
			</td>
			<td>
			{$cate_memo}
			</td>
		</tr>
	";
		$i++;
	}	
	$main.="
	</table>
	</div>
	";

	//於頁腳或是JS檔中建立以下的sweetalert->code
	$xoopsurl=XOOPS_URL;
/*	$main.="
	<script>var xoopsjsurl='{$xoopsurl}';</script> //傳入Xoops路徑
	<script>
		$(document).ready(function(){
			$(\".honordle\").each(function(index) {   //each所有class='honordle'的元件，並產生index值(0.1.2.3)
				$(\"#slowdivbotton\"+index+\"\").click(function() {  
				//#slowdivbotton加上index就會是#slowdivbotton0,#slowdivbotton1,#slowdivbotton2，與PHP檔案中的按鈕ID一致
				$cate_sn=$(\"#slowdivbotton\"+index+\"\").attr(\"mane\");  //取得#slowdivbotton+index+的mane數值。
				swal({
				title: \"您確定要刪除嗎？\",
				text: \"您確定要刪除這筆資料？\",
				type: \"warning\",
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonText: \"是的，我要刪除\",
				cancelButtonText: \"取消刪除！\",
				confirmButtonColor: \"#ec6c62\"
				},

					function(isConfirm){
					if (isConfirm) {
					$.ajax({
					url: xoopsjsurl+'/modules/ajax/ajax.php',   //ajax引入檔
					type: 'POST',
					data: { id: '1',cate_sn: $cate_sn},  //傳入兩個數值到/ajax檔案中
					}).done(function(data) {
					swal(\"操作成功!\", \"已成功刪除資料！\", \"success\");
					getMyArtic();  //更新頁面函示
					}).error(function(data) {
					swal(\"OMG\", \"刪除操作失敗了！\", \"error\");
					});
					} else {
					swal(\"取消！\", \"你的資料是安全的:)\",
					\"error\");
					}
						function getMyArtic(){  //刪除元素
						$(this).click(function() {
						// location.reload()
						$t=$(\".t\"+index+\"\");
						$t.remove();
						});
						}
					});
				});
			});
		});
	</script>	
	";
*/	
	echo $main;
}

//編輯單一表單
function form_cate($cate_sn=0){
  global $xoopsDB,$xoopsModule,$xoopsConfig;
	$DIRNAME=$xoopsModule->getVar('dirname');
	$sql = "select * from ".$xoopsDB->prefix("lin_mb_cate")." where `cate_sn`= $cate_sn";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	

	$main = "
	<div style='margin:0em 4em; '>
	<form action='?op=show&cate_sn={$cate_sn}' method='post' id='myForm' enctype='multipart/form-data'>
		<div style='width:100%; color: #2F0000; font-size:0.5cm; background-color:#FFF8D7; text-shadow: 0.1em 0.1em 0.2em black'><p>
		名稱：<input type='text' name='cate_title' size='25' value='{$cate_title}' id='cate_title' class='validate[required , min[1], max[100]]'>1<p>
		排序：<input type='text' name='cate_sort' size='10' value='{$cate_sort}' id='cate_sort' class='validate[required , min[1], max[50]]'>2<p>
		啟用：<input type='text' name='cate_enable' size='20' value='{$cate_enable}' id='cate_enable' >3<p>
		備註：<input type='text' name='cate_memo' size='20' value='{$cate_memo}' id='cate_memo' >4<p>
	<tr><th colspan='2'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='hidden' name='uid' value='{$uid}'>
	<input type='hidden' name='cate_sn' value='{$cate_sn}'>
	<center>
		<input class='btn btn-warning' type='submit' value='存檔' />
		<input class='btn btn-default' type='button' onclick=\"window.location.replace('?op=show&cate_sn={$cate_sn}')\" value='取消' /> 
	</center>
	</th></tr>
	</form>
	</div>";
	
	echo $main;
	
}

//秀出單一表單
function show_cate($cate_sn){
	global $xoopsDB,$xoopsModule,$xoopsUser, $xoopsTpl;
	$DIRNAME=$xoopsModule->getVar('dirname');
	
	$sql = "select * from ".$xoopsDB->prefix("lin_mb_cate")." where `cate_sn`= $cate_sn";
	//資料庫的變數： $cate_sn ,$cate_title ,$cate_sort ,$cate_enable ,$cate_memo 
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$all=$xoopsDB->fetchArray($result);

	foreach($all as $k=>$v){
	  $$k=$v;
	}
	//判斷空值，回列表
	if (empty($cate_com)){
		list_cate();
		return;
	}

	if($xoopsUser){//管理員使用
		if($xoopsUser->isAdmin ()){
		$admin= "
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form' class='btn btn-primary'>【新增】</a>
		</div>
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form&mb_sn={$cate_sn}' class='btn btn-warning'>【編輯】</a>
		</div>
		<div style='float:left;'>
		<a href='javascript:delete_action({$cate_sn});' class='btn btn-danger'>【刪除】</a>
		</div>
		";
		}}

		
}

//以流水號讀取某筆資料
function get_cate($cate_sn){
	global $xoopsDB,$xoopsModule;
	$DIRNAME=$xoopsModule->getVar('dirname');

	if (empty($cate_sn)) {
		return;
	}

	//cate_title	cate_readme	image_counter
	$sql = "select * from ".$xoopsDB->prefix("lin_mb_cate")." where `cate_sn` = $cate_sn";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data = $xoopsDB->fetchArray($result);

  return $data;
}

//查詢類別內是否有資料
function chk_cate($cate_sn){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');

  //cate_title	cate_readme	image_counter
  $sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn` = $cate_sn";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
 
  
  //***************************************************************************/
  $main="";

  return $main ;
}


//新增資料到lin_mb_cate中
function insert_cate()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
    //取得最後新增資料的流水編號

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $myts = \MyTextSanitizer::getInstance();
    $_POST['cate_title'] = $myts->addSlashes($_POST['cate_title']);
    $_POST['cate_sort'] = $myts->addSlashes($_POST['cate_sort']);
    $_POST['cate_enable'] = $myts->addSlashes($_POST['cate_enable']);
    $_POST['cate_memo'] = $myts->addSlashes($_POST['cate_memo']);

    $sql = 'insert into `' . $xoopsDB->prefix('lin_member') . "`
  (`cate_title` , `cate_sort` , `cate_enable` , `cate_memo`)
  values('{$_POST['cate_title']}' , '{$_POST['cate_sort']}' , 1 , '{$_POST['cate_memo']}')";
 
	$xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $cate_sn = $xoopsDB->getInsertId();

//資料庫的變數： $cate_sn ,$cate_title ,$cate_sort ,$cate_enable ,$cate_memo 

/*	`cate_sn` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '類別編號',
	`cate_title` VARCHAR(255) NOT NULL COMMENT '類別標題' COLLATE 'utf8_general_ci',
	`cate_sort` SMALLINT(6) UNSIGNED NOT NULL COMMENT '類別排序',
	`cate_enable` ENUM('1','0') NOT NULL COMMENT '是否啟用' COLLATE 'utf8_general_ci',
	`cate_memo` VARCHAR(255) NOT NULL COMMENT '備註' COLLATE 'utf8_general_ci',
*/




    return $cate_sn;
}

//更新lin_mb_cate某一筆資料
function update_cate($cate_sn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
	
//    $mb = get_lin_member($cate_sn);
    //取得使用者編號
//    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
//
    $myts = \MyTextSanitizer::getInstance();
	$cate_sn = $myts->addSlashes($_POST['cate_sn']);
    $_POST['cate_title'] = $myts->addSlashes($_POST['cate_title']);
    $_POST['cate_sort'] = $myts->addSlashes($_POST['cate_sort']);
    $_POST['cate_enable'] = $myts->addSlashes($_POST['cate_enable']);
    $_POST['cate_memo'] = $myts->addSlashes($_POST['cate_memo']);
//die("值：".$cate_sn."，測試：".$_POST['cate_title']);

    $sql = 'update `' . $xoopsDB->prefix('lin_member') . "` set
    `cate_title` = '{$_POST['cate_title']}' ,
    `cate_sort` = '{$_POST['cate_sort']}' ,
    `cate_enable` = '{$_POST['cate_enable']}' ,
    `cate_memo` = '{$_POST['cate_memo']}' ,
  where `cate_sn` = '$cate_sn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
	

//    $_POST['cate_title'] = change_charset($_POST['cate_title'], false);
//    $mb['cate_title'] = change_charset($mb['cate_title'], false);

/*    if (is_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['cate_title']}")) {
        if ($mb['cate_title'] != $_POST['cate_title']) {
            rename(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['cate_title']}", XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['cate_title']}");
        }
    } else {
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['cate_title']}");
    }
*/
    header("location: {$_SERVER['PHP_SELF']}?op=show&cate_sn={$cate_sn}");
//    return $cate_sn;

}




/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {

    // case "xxx":
    // xxx();
    // header("location:{$_SERVER['PHP_SELF']}");
    // exit;
	
    case "form_cate"://編輯表單
        form_cate();
        break;
    
    case "insert_cate"://新增資料
        insert_cate();
        break;
    
    case "update_cate"://更新資料//修改資料
        update_cate($cate_sn);
        break;

	case "delete"://刪除資料
        delete_cate($cate_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        break;

    default://列表//
        list_cate();
        break;
}

include_once 'footer.php';
