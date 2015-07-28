<?php

// 数据文件
$data_file = './data.json';
// 允许的uri地址。@to_do：禁止非法的uri
$allowed_uri = '';
// 可否编辑
$editable = FALSE;


// Get data and password
$data = json_decode(file_get_contents($data_file), true);
// unset after geting passwords
$viewpass = $data['viewpass'];
$adminpass = $data['adminpass'];
unset($data['viewpass'], $data['adminpass']);
// Get method & do action
$action = $_REQUEST['a'];
switch ($action) {
	// 显示
	case 'show':
		if( verify_pass( trim($_REQUEST['vpass']), $viewpass ) )
		{
			$data['errno'] = '0';
			$data['show'] = 1;
		}
		else  // wrong pass
		{
			$data['errno'] = '0';
			$data['show'] = 0;
			$data['content'] = '';

		}

		echo json_encode( $data );
		break;

	// 更新 @to_do 密码长度没有限制
	case 'update':
		$apass = trim($_POST['admin_password']);
		if( verify_pass( $apass, $adminpass ) )
		{
			$data2save['title'] = $_POST['title'];
			$data2save['subtitle'] = $_POST['subtitle'];
			$data2save['content'] = $_POST['content'];
			$data2save['viewpass'] = $_POST['view_password'];
			$data2save['adminpass'] = $apass;

			if( file_put_contents($data_file, json_encode($data2save)) )
			{
				echo json_encode(array('errno'=>'0', 'notice'=>'数据已成功保存'));
			}
		}
		else  // wrong pass
		{
			echo json_encode(array('errno'=>'3', 'error'=>'管理密码错误'));
		}

		break;

	default:
		# code...
		break;
}


/**
* Verify password
* @input: string. The input password to verified.
* @pass: string. The right password.
* @return: boolean. If password string is NOT NULL and is differet with the input than return FALSE,
* otherwise TRUE.
*/

function verify_pass($input, $pass) {
	if( strlen( $pass ) > 0 && $input != $pass ) return FALSE;

	return TRUE;
}