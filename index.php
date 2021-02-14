<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "index.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";
include_once XOOPS_ROOT_PATH . "/function.php";

/*-----------function區--------------*/



/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {

    // case "xxx":
    // xxx();
    // header("location:{$_SERVER['PHP_SELF']}");
    // exit;
    case "create"://新增資料--(程式在function.php)
        create();
        break;

    case "edit"://修改資料--(程式在function.php)
        edit();
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

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
