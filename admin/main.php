<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "adm_main.tpl";
include_once "header.php";
include_once "../function.php";

//
include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";

/*-----------function區--------------*/
////lin_member編輯表單
function lin_member_form($mb_sn = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    //include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
    //include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

    //抓取預設值
    if (!empty($mb_sn)) {
        $DBV = get_lin_member($mb_sn);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「mb_sn」欄位預設值
    $mb_sn = !isset($DBV['mb_sn']) ? $mb_sn : $DBV['mb_sn'];
    $xoopsTpl->assign('mb_sn', $mb_sn);

    //設定「mb_com」欄位預設值
    $com = !isset($DBV['mb_com']) ? $mb_com : $DBV['mb_com'];
    $xoopsTpl->assign('mb_com', $mb_com);

    //設定「mb_name」欄位預設值
    $name = !isset($DBV['mb_name']) ? $mb_name : $DBV['mb_name'];
    $xoopsTpl->assign('mb_name', $mb_name);

    //設定「mb_mobile」欄位預設值
    $mobile = !isset($DBV['mb_mobile']) ? $mb_mobile : $DBV['mb_mobile'];
    $xoopsTpl->assign('mb_mobile', $mb_mobile);

    //設定「mb_phone」欄位預設值
    $phone = !isset($DBV['mb_phone']) ? $mb_phone : $DBV['mb_phone'];
    $xoopsTpl->assign('mb_phone', $mb_phone);

    //設定「mb_fax」欄位預設值
    $fax = !isset($DBV['mb_fax']) ? $mb_fax : $DBV['mb_fax'];
    $xoopsTpl->assign('mb_fax', $mb_fax);

    //設定「mb_email」欄位預設值
    $email = !isset($DBV['mb_email']) ? $mb_email : $DBV['mb_email'];
    $xoopsTpl->assign('mb_email', $mb_email);

    //設定「mb_url」欄位預設值
    $url = !isset($DBV['mb_url']) ? $mb_url : $DBV['mb_url'];
    $xoopsTpl->assign('mb_url', $mb_url);

    //設定「mb_location」欄位預設值
    $location = !isset($DBV['mb_location']) ? $mb_location : $DBV['mb_location'];
    $xoopsTpl->assign('mb_location', $mb_location);

    //設定「mb_uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $mb_uid = !isset($DBV['mb_uid']) ? $user_uid : $DBV['mb_uid'];
    $xoopsTpl->assign('mb_uid', $mb_uid);

    //設定「mb_last_update」欄位預設值
    $last_update = !isset($DBV['mb_last_update']) ? date('Y-m-d H:i:s') : $DBV['mb_last_update'];
    $xoopsTpl->assign('mb_last_update', $mb_last_update);

    //設定「mb_memo」欄位預設值
    $memo = !isset($DBV['mb_memo']) ? $mb_memo : $DBV['mb_memo'];
    $xoopsTpl->assign('mb_memo', $mb_memo);

    $op = (empty($mb_sn)) ? 'insert_lin_member' : 'update_lin_member';
    //$op="replace_lin_member";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    //評鑑說明
    $ck = new CkEditor('lin_member', 'mb_name', $mb_name);
    $ck->setHeight(100);
    $editor = $ck->render();


//會員類別選擇(如果讓【會員】自行變更內容，這個項目要設定隱藏)
	$selected=($cate_sn==0)?" selected=selected":"";
	$stop_level=1;
	$select_cate_sn="
	類別：<select name='cate_sn' size=1>
		".get_cate_option($cate_sn,1,0)."
	</select> 
	";

	$main.="
		<div style='background-color:yellow;'>
		{$select_cate_sn}
		</div>
		<div style=''>
		<span style='color:#fff; font-weight:bold;'>
		名稱：<input type='text' name='mb_name' size='40' value='{$mb_com}' id='mb_name' >
		</span>
		</div>
		<div style=''>
		姓名：<input type='text' name='mb_name' size='5' value='{$mb_name}' id='mb_name' ><br>
		行動：<input type='text' name='mb_mobile' size='5' value='{$mb_mobile}' id='mb_mobile' ><br>
		電話：<input type='text' name='mb_phone' size='5' value='{$mb_phone}' id='mb_phone' ><br>
		傳真：<input type='text' name='mb_fax' size='40' value='{$mb_fax}' id='mb_fax' ><br>
		信箱：<input type='text' name='mb_email' size='40' value='{$mb_email}' id='mb_email' ><br>
		地址：<input type='text' name='mb_location' size='40' value='{$mb_location}' id='mb_location' ><br>
		網址：<input type='text' name='mb_url' size='40' value='{$mb_url}' id='mb_url' ><br>
		</div>
		<div style='background-color:#006000;'>
			<span style='color:#fff; font-weight:bold;'>會員簡介</span>
		</div>
		<div>
		<textarea name='mb_memo' cols='50' rows=8 id='mb_memo'>{$mb_memo}</textarea>
		</div>
		
	";
//資料庫的變數： $mb_sn ,$cate_sn ,$com ,$name ,$mobile ,$phone ,$fax ,$email ,$url ,$location ,last_update ,$memo 
	echo $main;
}

//新增資料到lin_member中
function insert_lin_member()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $myts = \MyTextSanitizer::getInstance();
    $_POST['com'] = $myts->addSlashes($_POST['com']);
    $_POST['name'] = $myts->addSlashes($_POST['name']);
    $_POST['mobile'] = $myts->addSlashes($_POST['mobile']);
    $_POST['phone'] = $myts->addSlashes($_POST['phone']);
    $_POST['fax'] = $myts->addSlashes($_POST['fax']);
    $_POST['email'] = $myts->addSlashes($_POST['email']);
    $_POST['url'] = $myts->addSlashes($_POST['url']);
    $_POST['location'] = $myts->addSlashes($_POST['location']);
    $_POST['memo'] = $myts->addSlashes($_POST['memo']);

    $sql = 'insert into `' . $xoopsDB->prefix('lin_member') . "`
  (`com` , `name` , `mobile` , `phone` , `fax` , `email` , `url` , `location` , `last_update` , `memo`)
  values('{$_POST['com']}' , '{$_POST['name']}' , '{$_POST['mobile']}' , '{$_POST['phone']}' , '{$_POST['fax']}' , '{$_POST['email']}' , '{$_POST['url']}' , '{$_POST['location']}' , '" . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . "' , '{$_POST['memo']}')";
 
//	$xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

//資料庫的變數： $mb_sn ,$cate_sn ,$mb_com ,$mb_name ,$mb_mobile ,$mb_phone ,$mb_fax ,$mb_email ,$mb_url ,$mb_location ,$mb_last_update ,$mb_memo 

    //取得最後新增資料的流水編號
    $mb_sn = $xoopsDB->getInsertId();
//die("取得：{$mb_sn}有嗎？");

    $_POST['com'] = change_charset($_POST['com'], false);

    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['com']}");

    return $mb_sn;
}

//更新lin_member某一筆資料
function update_lin_member($mb_sn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
    $mb = get_lin_member($mb_sn);

    //取得使用者編號
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';

    $myts = \MyTextSanitizer::getInstance();
    $_POST['com'] = $myts->addSlashes($_POST['com']);
    $_POST['name'] = $myts->addSlashes($_POST['name']);

    $sql = 'update `' . $xoopsDB->prefix('lin_member') . "` set
   `com` = '{$_POST['com']}' ,
   `name` = '{$_POST['name']}' ,
   `mb_enable` = '{$_POST['mb_enable']}' ,
   `mb_uid` = '{$uid}' ,
   `last_update` = '" . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . "'
  where `mb_sn` = '$mb_sn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $_POST['com'] = change_charset($_POST['com'], false);
    $mb['com'] = change_charset($mb['com'], false);

    if (is_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['com']}")) {
        if ($mb['com'] != $_POST['com']) {
            rename(XOOPS_ROOT_PATH . "/uploads/lin_member/{$mb['com']}", XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['com']}");
        }
    } else {
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lin_member/{$_POST['com']}");
    }

    return $mb_sn;
}

//刪除lin_member某筆資料資料
function delete_lin_member($mb_sn = '')
{
    global $xoopsDB, $isAdmin;
    delete_lin_member_cate($mb_sn, true);
    $sql = 'delete from `' . $xoopsDB->prefix('lin_member') . "` where `mb_sn` = '{$mb_sn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    case "insert_lin_member"://新增資料--(程式在function.php)
        insert_lin_member();
        break;

    case "update_lin_member"://修改資料--(程式在function.php)
        update_lin_member();
        break;

    //更新資料
    case 'lin_member_form':
        lin_member_form();
        break;

		case "delete"://刪除資料--(程式在function.php)
        del();
        break;

    case "show"://秀出結果區--(程式在function.php)
        show();
        break;


    default://列表--(程式在function.php)
        show_content($located=1);
        break;
}

include_once 'footer.php';
