
<{include file='sweet-alert.tpl'}>
	<script type='text/javascript' src='class/sweet-alert/sweetalert.min.js'></script>
	<link rel='stylesheet' type='text/css' href='class/sweet-alert/sweetalert.css' />
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>

<{if $op=="lin_member_form"}>
lin_member_form

	<div style='margin:0em 4em; '>
	<form action='?op=show&mb_sn=<{$mb_sn}>' method='post' id='myForm' enctype='multipart/form-data'>
		<div style='width:100%; color: #2F0000; font-size:0.5cm; background-color:#FFF8D7; text-shadow: 0.1em 0.1em 0.2em black'>
		<div class='btn btn-success disabled btn-block'><{$Form_title}></div><p>
		<{$select_cate_sn}><p>
		名稱：<input type='text' name='mb_com' size='25' value='<{$mb_com}>' id='mb_com' class='validate[required , min[1], max[100]]'><p>
		姓名：<input type='text' name='mb_name' size='10' value='<{$mb_name}>' id='mb_name' class='validate[required , min[1], max[50]]'><p>
		行動：<input type='text' name='mb_mobile' size='20' value='<{$mb_mobile}>' id='mb_mobile' ><p>
		電話：<input type='text' name='mb_phone' size='20' value='<{$mb_phone}>' id='mb_phone' ><p>
		傳真：<input type='text' name='mb_fax' size='20' value='<{$mb_fax}>' id='mb_fax' ><p>
		信箱：<input type='text' name='mb_email' size='25' value='<{$mb_email}>' id='mb_email' ><p>
		地址：<input type='text' name='mb_location' size='25' value='<{$mb_location}>' id='mb_location' ><p>
		網址：<input type='text' name='mb_url' size='25' value='<{$mb_url}>' id='mb_url' '><p>
		<div class='btn btn-success disabled btn-block'>會員簡介</div>
		<div >
		<textarea name='mb_memo' style='width:100%;height:300px; font-size:0.5cm;' id='mb_memo'><{$mb_memo}></textarea>
		</div>
		</div>
	<tr><th colspan='2'>
	<input type='hidden' name='op' value='<{$op}>'>
	<input type='hidden' name='uid' value='<{$uid}>'>
	<input type='hidden' name='mb_sn' value='<{$mb_sn}>'>
	<center>
		<input class='btn btn-warning' type='submit' value='存檔' />
		<{if $mb_sn}>
		<a href='?op=update_lin_member&mb_sn=<{$mb_sn}>' class='btn btn-danger'>存檔</a>
		<{else}>
		<a href='?op=insert_lin_member>' class='btn btn-danger'>存檔</a>
		<{/if}>

		<input class='btn btn-default' type='button' onclick="window.location.replace('?op=show&mb_sn=<{$mb_sn}>')" value='取消' /> 
	</center>
	</th></tr>
	</form>

<{elseif $op=="show"}>
	<div style='margin:0em 4em; '>
<!--	<{$show_locat}> -->
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form' class='btn btn-primary'>【新增】</a>
		</div>
		<div style='float:left;'>
		<a href='/modules/member/admin/main.php?op=lin_member_form&mb_sn=<{$mb_sn}>' class='btn btn-warning'>【編輯】</a>
		</div>
		<div style='float:left;'>
		<a href='javascript:delete_action(<{$mb_sn}>);' class='btn btn-danger'>【刪除】</a>
		</div>
		<div style='float:left;'>
		<a href='?cate_sn=<{$cate_sn}>' class='btn btn-success'>【列表】</a>
		</div>
	<div style='background-color:#fff; clear:left;'>
	</div>
	<div style='height:30px; line-height:30px; font-size:17; background-color:#000; color:yellow;'>
		【<{$cate_name}>】
		<span style='color:#fff; font-weight:bold;' class='disabled' ><{$mb_com}></span>
	</div>
	<div style=' background-color:#F3F3FA;' >
<!--
	<span class='btn disabled'>類別：<span style='color:green;' ><{$cate_name}></span></span><br>
	<span class='btn disabled'>名稱：<span style='color:blue;' ><{$mb_com}></span></span><br>
-->
	<span class='btn disabled'>姓名：<{$mb_name}></span><br>
	<span class='btn disabled'>行動：<{$mb_mobile}></span><br>
	<span class='btn disabled'>電話：<{$mb_phone}></span><br>
	<span class='btn disabled'>傳真：<{$mb_fax}></span><br>
	<span class='btn disabled'>信箱：<a href='mailto:<{$mb_email}>'><{$mb_email}></a></span><br>
	<span class='btn disabled'>地址：<a href='http://maps.google.com.tw/maps?f=q&hl=zh-TW&geocode=&q=<{$mb_location}>' target=_blank title='連結google地圖'><{$mb_location}></a></span><br>
	<span class='btn disabled'>網址：<a href='<{$mb_url}>' target='blank'><{$mb_url}></a></span>
	</div>
	<div style='height:30px; line-height:30px; font-size:17; background-color:#006000;'>
		<span style='color:#fff; font-weight:bold;'>會員簡介</span>
	</div>
	<div style='background-color:#F3F3FA; font-family:Microsoft JhengHei; font-size:17px; margin:1em 1em 5em;'><p><{$mb_memo}></p>
	</div>
	</div>
	
	<script type='text/javascript'>
	  function delete_action(id){
		swal({
		  title: '確定要刪除嗎？',
		  text: '刪除後資料就消失救不回來囉！',
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#DD6B55',
		  confirmButtonText: '是！含淚刪除！',
		  cancelButtonText: '不...別刪',
		  closeOnConfirm: false
		}, function(){
		  swal('OK！刪掉惹！', '該資料已經隨風而逝了...', 'success');

		  location.href='?op=delete&mb_sn=' + id;
		});
	  }
	</script>

<{else}>

	<{$content}>

<{/if}>