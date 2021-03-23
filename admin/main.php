<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "adm_main.tpl";
include_once "header.php";
include_once "../function.php";
include_once XOOPS_ROOT_PATH . "/modules/tadtools/ck.php";

/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {

    case 'lin_member_form'://編輯表單--(程式在function.php)
        lin_member_form();
        break;

    case "insert_lin_member"://新增資料--(程式在function.php)
        insert_lin_member();
        break;

    case "update_lin_member"://修改資料--(程式在function.php)
        update_lin_member();
        break;
    
	case "delete"://刪除資料--(程式在function.php)
        delete_lin_member();
        header("location: {$_SERVER['PHP_SELF']}");
        break;

    case "show"://秀出結果區--(程式在function.php)
        show($located=1);
        break;

    default://列表--(程式在function.php)
        show_content($located=1);
        break;
}

/*-----------秀出結果區--------------*/

include_once 'footer.php';
