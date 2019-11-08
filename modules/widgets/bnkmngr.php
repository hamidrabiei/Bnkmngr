<?php
/* ========================================
						   _
	 _ _ ___ ___ ___ ___  |_|___
	| | | -_|   | . |   |_| |  _|
	 \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201806
version 3.0
=========================================*/

function widget_bnkmngr($vars) {

	$title = 'آخرین فیش های ثبت شده';
    $content = '<table width="100%" bgcolor="#cccccc" cellspacing="1" align="center">
	<tr bgcolor="#efefef" style="text-align:center;font-weight:bold;"><td>كد</td><td>تاریخ</td><td>مشتری مرتبط</td><td>مبلغ</td><td>وضعیت</td><td></td></tr>';

	$result = mysql_query("SELECT * FROM `mod_venon_bnkmngr_bills` ORDER BY  `mod_venon_bnkmngr_bills`.`id` DESC LIMIT 0, 5");
	while ($data = mysql_fetch_array($result)) {
		$id = $data['id'];
		$content .= '<tr bgcolor="#ffffff""><td>'.$data['id'].'</td><td>'.fromMySQLDate($data['date'],$data['date']).'</td>';

		//user list
		$username = mysql_query("SELECT firstname,lastname FROM `tblclients` WHERE id=$data[reluserid]");
		$userfetch = mysql_fetch_array($username);
		$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];
		$content .= '<td><a href="./clientssummary.php?userid='.$data[reluserid].'" title="'.$LANG['viewclient'].'" target="_blank">'. $userfullname .'</a></td><td>'.$data[amount].'</td>';

		if ($data['status']=='sent') {
			$content .= '<td style="background:#d7d7d7;">معلق</td>';
		} elseif ($data['status']=='fake') {
			$content .= '<td style="background:rgb(248, 129, 129);">تایید نشد</td>';
		} elseif ($data['status']=='active') {
			$content .= '<td style="background:rgb(157, 204, 151);">تایید شد</td>';
		}

		$content .= '<td><a href="addonmodules.php?module=v_bnkmngr&go=edit&id='. $data['id'] .'"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit" title="مشاهده فیش"></a></td>';
	}

	$content .= '</table><p style="text-align:center;"><a class="btn btn-default btn-small" href="./addonmodules.php?module=v_bnkmngr">فیش های ثبت شده</a></p>';

    return array( 'title' => $title, 'content' => $content );
}

add_hook("AdminHomeWidgets",1,"widget_bnkmngr");
?>
