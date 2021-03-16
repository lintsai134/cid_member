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

	$main="
	<div class='table-responsive'>
	<table border='1' cellspacing='0' cellpadding='0'  class='table'>
		<tr>
			<td bgcolor='#ffff00' width=10%>
			<center>編號</center>
			</td>
			<td bgcolor='#ffff00' width=40%>
			名稱
			</td>
			<td bgcolor='#ffff00' width=10%>
			排序
			</td>
			<td bgcolor='#ffff00' width=10%>
			是否啟用
			</td>
			<td bgcolor='#ffff00' width=30%>
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
	echo $main;
}
//讀取類別資料
function get_cate(){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');

  //cate_title	cate_readme	image_counter
  $sql = "select * from ".$xoopsDB->prefix("lin_mb_cate")." order by `cate_sn` ";
 
  
  //***************************************************************************/
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $main="";

  return $main ;
}

//查詢類別內是否有資料
function chk_cate($cate_sn){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');

  //cate_title	cate_readme	image_counter
  $sql = "select * from ".$xoopsDB->prefix("lin_member")." where `cate_sn` = $cate_sn";
 
  
  //***************************************************************************/
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
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
  ( `mb_com` , `cate_sort` , `cate_enable` , `cate_memo`)
  values('{$_POST['cate_title']}' , '{$_POST['cate_sort']}' , '1' , '{$_POST['cate_memo']}')";
 
	$xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $cate_sn = $xoopsDB->getInsertId();

//資料庫的變數： $cate_sn ,$cate_title ,$cate_sort ,$cate_enable ,$cate_memo 

/*	`cate_sn` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '類別編號',
	`cate_title` VARCHAR(255) NOT NULL COMMENT '類別標題' COLLATE 'utf8_general_ci',
	`cate_sort` SMALLINT(6) UNSIGNED NOT NULL COMMENT '類別排序',
	`cate_enable` ENUM('1','0') NOT NULL COMMENT '是否啟用' COLLATE 'utf8_general_ci',
	`cate_memo` VARCHAR(255) NOT NULL COMMENT '備註' COLLATE 'utf8_general_ci',
*/




    return $mb_sn;
}

//更新lin_mb_cate某一筆資料
function update_cate($cate_sn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
	
//    $mb = get_lin_member($mb_sn);
    //取得使用者編號
//    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
//
    $myts = \MyTextSanitizer::getInstance();
	$cate_sn = $myts->addSlashes($_POST['cate_sn']);
    $_POST['cate_title'] = $myts->addSlashes($_POST['cate_title']);
    $_POST['cate_sort'] = $myts->addSlashes($_POST['cate_sort']);
    $_POST['cate_enable'] = $myts->addSlashes($_POST['cate_enable']);
    $_POST['cate_memo'] = $myts->addSlashes($_POST['cate_memo']);
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




/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {

    // case "xxx":
    // xxx();
    // header("location:{$_SERVER['PHP_SELF']}");
    // exit;
	
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
