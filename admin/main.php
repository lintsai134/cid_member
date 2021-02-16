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

    //設定「com」欄位預設值
    $com = !isset($DBV['com']) ? null : $DBV['com'];
    $xoopsTpl->assign('com', $com);

    //設定「name」欄位預設值
    $name = !isset($DBV['name']) ? '' : $DBV['name'];
    $xoopsTpl->assign('name', $name);

    //設定「mb_enable」欄位預設值XXXXXXX
    $mb_enable = !isset($DBV['mb_enable']) ? '1' : $DBV['mb_enable'];
    $xoopsTpl->assign('mb_enable', $mb_enable);

    //設定「mb_uid」欄位預設值
    $user_uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $mb_uid = !isset($DBV['mb_uid']) ? $user_uid : $DBV['mb_uid'];
    $xoopsTpl->assign('mb_uid', $mb_uid);

    //設定「last_update」欄位預設值
    $last_update = !isset($DBV['last_update']) ? date('Y-m-d H:i:s') : $DBV['last_update'];
    $xoopsTpl->assign('last_update', $last_update);

    $op = (empty($mb_sn)) ? 'insert_lin_member' : 'update_lin_member';
    //$op="replace_lin_member";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    //評鑑說明
    $ck = new CkEditor('lin_member', 'name', $name);
    $ck->setHeight(100);
    $editor = $ck->render();

    $xoopsTpl->assign('name_editor', $editor);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('now_op', 'lin_member_form');
    $xoopsTpl->assign('next_op', $op);
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

    $sql = 'insert into `' . $xoopsDB->prefix('lin_member') . "`
  (`com` , `name` , `mb_enable` , `mb_uid` , `last_update`)
  values('{$_POST['com']}' , '{$_POST['name']}' , '{$_POST['mb_enable']}' , '{$uid}' , '" . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . "')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $mb_sn = $xoopsDB->getInsertId();

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
