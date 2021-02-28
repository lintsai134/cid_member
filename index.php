<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "index.tpl";
include_once "header.php";
include_once XOOPS_ROOT_PATH . "/header.php";
include_once XOOPS_ROOT_PATH . "/function.php";



/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {

    case "show"://秀出結果區--(程式在function.php)
        show($located=2);
        break;

    default://列表--(程式在function.php)
        show_content($located=2);
        break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH . '/footer.php';
