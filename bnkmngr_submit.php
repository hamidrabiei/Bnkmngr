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

define("CLIENTAREA", true);

require("init.php");
$ca = new WHMCS_ClientArea();
$uid = $ca->getUserID();

// get setting
$result = select_query('mod_venon_bnkmngr','*',array('setting'=>'options'));
$data = mysql_fetch_array($result);
$attachmaxsize = $data['attachmaxsize'];

if (isset($_POST['relbank'])){
	if ($_POST['amount']!= null and $_POST['peygirinum']!= null AND $_POST['amount']>0) {

		if ($_FILES["file"]["error"] != 4 AND isset($_FILES["file"])) {
			// uploader
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$extension = end(explode(".", $_FILES["file"]["name"]));
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < $attachmaxsize)
			&& in_array($extension, $allowedExts)) {
				if ($_FILES["file"]["error"] > 0) {
					Header('Location: index.php?m=v_bnkmngr&s=e1');
				} else {
					if (file_exists("modules/addons/v_bnkmngr/uploads/" . $subdate.'-'.$_FILES["file"]["name"])){
						// file exists
						Header('Location: index.php?m=v_bnkmngr&s=e1&error=exists');
						}
					else {
						$subdate = date('YmdHi');
						move_uploaded_file($_FILES["file"]["tmp_name"],
						"modules/addons/v_bnkmngr/uploads/" . $subdate.'-'.$_FILES["file"]["name"]);
						$filename = "modules/addons/v_bnkmngr/uploads/" . $subdate.'-'.$_FILES["file"]["name"];

						$table = "mod_venon_bnkmngr_bills";
						$values = array("date"=>$_POST['date'], "relinvoice"=>$_POST['relinvoice'], "paymenttype"=>$_POST['paymenttype'], "peygirinum"=>$_POST['peygirinum'], "amount"=>$_POST['amount'], "relbank"=>$_POST['relbank'], "fishnum"=>$_POST['fishnum'], "desc"=>$_POST['desc'], "attach"=>$filename, "reluserid"=>$_POST['reluserid'], "status"=>$_POST['status']);
						$newid = insert_query($table,$values);
						$bill = 'success';

						Header('Location: index.php?m=v_bnkmngr&p=list&s=success');
					}
				}
			} else {
			  Header('Location: index.php?m=v_bnkmngr&s=e1&error=upload');
			}
		} else {
			#proper date (j to g)
			$datey = $_POST['datey'];			$datem = $_POST['datem'] ;			$dated = $_POST['dated'];
			if (($dated>31 OR $dated<1) OR ($datem>12 OR $datem<1) OR ($datey>1500 OR $datey<1300)) {
				// date error
				Header('Location: index.php?m=v_bnkmngr&s=date');
			} else {
				$datef = "$datey/$datey/$datey";
				if (!function_exists('jalali_to_gregorian')) {
					include_once('modules/addons/v_bnkmngr/jdf.php');
				}
				$date = jalali_to_gregorian($datey,$datem,$dated, '/');
				$date .= date(" G:i");

				#proper amount
				$amount = str_replace (',', '', $_POST['amount']);

				#table
				$table = "mod_venon_bnkmngr_bills";
				$values = array("date"=>$date, "relinvoice"=>$_POST['relinvoice'], "paymenttype"=>$_POST['paymenttype'], "peygirinum"=>$_POST['peygirinum'], "amount"=> $amount, "relbank"=>$_POST['relbank'], "fishnum"=>$_POST['fishnum'], "desc"=>$_POST['desc'], "attach"=>$filename, "reluserid"=>$uid, "status"=>$_POST['status']);
				$newid = insert_query($table,$values);
				$bill = 'success';

				Header('Location: index.php?m=v_bnkmngr&p=list&s=success');
			}
		}
	} else {
		// req fileds not filled
		if(isset($_POST['amount'])){$string .= '&amount='.$_POST['amount'];}
		if(isset($_POST['peygirinum'])){$string .= '&peygirinum='.$_POST['peygirinum'];}
		if(isset($_POST['relinvoice'])){$string .= '&invoiceid='.$_POST['relinvoice'];}

		Header('Location: index.php?m=v_bnkmngr&s=false'. $string);
	}
} else {
	// access directly
	Header('Location: index.php?m=v_bnkmngr');
}
?>
