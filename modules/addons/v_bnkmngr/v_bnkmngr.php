<?php
if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

/* ========================================
						   _
	 _ _ ___ ___ ___ ___  |_|___
	| | | -_|   | . |   |_| |  _|
	 \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201806
version 3.0
سازگاری با نسخه 7
امکان ویرایش تاریخ ثبت فیش در مدیریت
غیرفعال سازی ماژول بدون حذف اطلاعات فیش ها


=========================================*/

function v_bnkmngr_config() {
    $configarray = array(
    "name" => "Venon Bank Manager",
    "description" => "Manage offline payments",
    "version" => "3.0",
    "author" => "Venon Web Developers. Venon.ir",
    "language" => "farsi",
    "fields" => array(
        // "License" => array ("FriendlyName" => "License", "Type" => "text", "Size" => "25", "Description" => "Enter your venon_bnkmngr License number", "Default" => "venon-", ),
    ));
    return $configarray;
}

function v_bnkmngr_activate() {
    # Create Custom DB Table
    $query = "CREATE TABLE IF NOT EXISTS `mod_venon_bnkmngr` (
	  `setting` text NOT NULL,
	  `attach` int(11) NOT NULL,
	  `attachformat` text NOT NULL,
	  `attachmaxsize` int(11) NOT NULL,
	  `jdate` int(11) NOT NULL,
	  `status` text,
	  `localkey` text
	)";
	$result = mysql_query($query);

	$query = "CREATE TABLE IF NOT EXISTS `mod_venon_bnkmngr_banks` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `bankname` text CHARACTER SET utf8 COLLATE utf8_bin,
	  `enable` int(11) NOT NULL,
	  `banklogo` text,
	  `accountnumber` text,
	  `cartnumber` text,
	  `shabanumber` text,
	  `accountholder` text CHARACTER SET utf8 COLLATE utf8_bin,
	  `displayorder` int(11) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	)";
	$result = mysql_query($query);

	$query = "CREATE TABLE IF NOT EXISTS `mod_venon_bnkmngr_bills` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `date` datetime NOT NULL,
	  `amount` int(25) NOT NULL,
	  `relinvoice` text,
	  `relbank` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	  `reluserid` int(11) NOT NULL,
	  `paymenttype` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	  `status` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	  `peygirinum` text,
	  `fishnum` text,
	  `desc` text CHARACTER SET utf8 COLLATE utf8_bin,
	  `attach` text,
	  PRIMARY KEY (`id`)
	)";
	$result = mysql_query($query);

	# Insert deafualt options
	$query = "INSERT INTO `mod_venon_bnkmngr` (`setting`, `attach`, `attachformat`, `attachmaxsize`, `jdate`, `status`, `localkey`) VALUES
('options', 0, '', 1048576, 1, 'Active', NULL);";
	$result = mysql_query($query);

	# Insert banks
	$query = "INSERT INTO `mod_venon_bnkmngr_banks` (`id`, `bankname`, `enable`, `banklogo`, `accountnumber`, `cartnumber`, `shabanumber`, `accountholder`, `displayorder`) VALUES (1, 'بانك كشاورزي', 0, 'modules/addons/v_bnkmngr/images/bank_agri.png', NULL, NULL, NULL, NULL, 10), (2, 'بانك انصار', 0, 'modules/addons/v_bnkmngr/images/bank_ansar.png', NULL, NULL, NULL, NULL, 10), (3, 'بانك دي', 0, 'modules/addons/v_bnkmngr/images/bank_dey.png', NULL, NULL, NULL, NULL, 10), (4, 'بانك اقتصاد نوين', 0, 'modules/addons/v_bnkmngr/images/bank_en.png', NULL, NULL, NULL, NULL, 10), (5, 'بانك قوامين', 0, 'modules/addons/v_bnkmngr/images/bank_ghavamin.png', NULL, NULL, NULL, NULL, 10), (6, 'بانك كارآفرين', 0, 'modules/addons/v_bnkmngr/images/bank_karafarin.png', NULL, NULL, NULL, NULL, 10), (7, 'بانك مسكن', 0, 'modules/addons/v_bnkmngr/images/bank_maskan.png', NULL, NULL, NULL, NULL, 10), (8, 'بانك ملت', 0, 'modules/addons/v_bnkmngr/images/bank_mellat.png', NULL, NULL, NULL, NULL, 10), (9, 'بانك ملي', 0, 'modules/addons/v_bnkmngr/images/bank_melli.png', NULL, NULL, NULL, NULL, 10), (10, 'بانك پارسيان', 0, 'modules/addons/v_bnkmngr/images/bank_parsian.png', NULL, NULL, NULL, NULL, 10), (11, 'بانك پاسارگاد', 0, 'modules/addons/v_bnkmngr/images/bank_pasargad.png', NULL, NULL, NULL, NULL, 10), (12, 'بانك رفاه كارگران', 0, 'modules/addons/v_bnkmngr/images/bank_refah.png', NULL, NULL, NULL, NULL, 10), (13, 'بانك صادرات', 0, 'modules/addons/v_bnkmngr/images/bank_saderat.png', NULL, NULL, NULL, NULL, 10), (14, 'بانك سامان', 0, 'modules/addons/v_bnkmngr/images/bank_saman.png', NULL, NULL, NULL, NULL, 10), (15, 'بانك صنعت و معدن', 0, 'modules/addons/v_bnkmngr/images/bank_sanatomadan.png', NULL, NULL, NULL, NULL, 10), (16, 'بانك سرمايه', 0, 'modules/addons/v_bnkmngr/images/bank_sarmaye.png', NULL, NULL, NULL, NULL, 10), (17, 'بانك سپه', 0, 'modules/addons/v_bnkmngr/images/bank_sepah.png', NULL, NULL, NULL, NULL, 10), (18, 'بانك شهر', 0, 'modules/addons/v_bnkmngr/images/bank_shahr.png', NULL, NULL, NULL, NULL, 10), (19, 'بانك سينا', 0, 'modules/addons/v_bnkmngr/images/bank_sina.png', NULL, NULL, NULL, NULL, 10), (20, 'بانك تجارت', 0, 'modules/addons/v_bnkmngr/images/bank_tejarat.png', NULL, NULL, NULL, NULL, 10), (21, 'بانك توسعه و تعاون', 0, 'modules/addons/v_bnkmngr/images/bank_tosee.png', NULL, NULL, NULL, NULL, 10),(22, ' پست بانک', 0, 'modules/addons/v_bnkmngr/images/bank_post.png', NULL, NULL, NULL, NULL, 50),(23, 'بانک گردشگری', 0, 'modules/addons/v_bnkmngr/images/bank_gardeshgari.png', NULL, NULL, NULL, NULL, 50),(24, 'بانک ایران زمین', 0, 'modules/addons/v_bnkmngr/images/bank_iranzamin.png', NULL, NULL, NULL, NULL, 50),(25, 'بانک خاوران', 0, 'modules/addons/v_bnkmngr/images/bank_khavaran.png', NULL, NULL, NULL, NULL, 50),(26, 'بانک آینده', 0, 'modules/addons/v_bnkmngr/images/bank_ayandeh.png', NULL, NULL, NULL, NULL, 50),(27, 'PayPal', 0, 'modules/addons/v_bnkmngr/images/paypal.png', NULL, NULL, NULL, NULL, 50);";
	$result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'Venon bank manager addon successfully installed');
    return array('status'=>'error','description'=>'Error: For further assistance, please contact us @venon.ir');
    return array('status'=>'info','description'=>'Select your options below');

}

function v_bnkmngr_deactivate() {
    # Remove Custom DB Table
   /*$query = "DROP TABLE `mod_venon_bnkmngr`, `mod_venon_bnkmngr_banks`, `mod_venon_bnkmngr_bills`;";
	 $result = mysql_query($query);*/

	$query = "DROP TABLE `mod_venon_bnkmngr`;";
	$result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'Venon bank manager addon successfully uninstalled');
    return array('status'=>'error','description'=>'Error: For further assistance, please contact us @venon.ir');
    return array('status'=>'info','description'=>'');

}

function v_bnkmngr_upgrade($vars) {
    $version = $vars['version'];

    # Run SQL Updates for V1.0 to V2.1
	if ($version < 2.1) {
		full_query("INSERT INTO `mod_venon_bnkmngr_banks` (`id`, `bankname`, `enable`, `banklogo`, `accountnumber`, `cartnumber`, `shabanumber`, `accountholder`, `displayorder`) VALUES (22, ' پست بانک', 0, 'modules/addons/v_bnkmngr/images/bank_post.png', NULL, NULL, NULL, NULL, 50),(23, 'بانک گردشگری', 0, 'modules/addons/v_bnkmngr/images/bank_gardeshgari.png', NULL, NULL, NULL, NULL, 50),(24, 'بانک ایران زمین', 0, 'modules/addons/v_bnkmngr/images/bank_iranzamin.png', NULL, NULL, NULL, NULL, 50),(25, 'بانک خاوران', 0, 'modules/addons/v_bnkmngr/images/bank_khavaran.png', NULL, NULL, NULL, NULL, 50),(26, 'بانک آینده', 0, 'modules/addons/v_bnkmngr/images/bank_ayandeh.png', NULL, NULL, NULL, NULL, 50),(27, 'PayPal', 0, 'modules/addons/v_bnkmngr/images/paypal.png', NULL, NULL, NULL, NULL, 50);");
	}
}


function v_bnkmngr_output($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $LANG = $vars['_lang'];


	echo '<div class="contexthelp"><a href="http://www.venon.ir" target="_blank"><img src="images/icons/help.png" border="0" align="absmiddle">'.$LANG['venonhelp'].'</a></div>';
    echo '<p>'.$LANG['intro'].'</p><p>'.$LANG['description'].'</p>';

	// tabs
	echo '<div id="clienttabs">
			<ul class="nav nav-tabs admin-tabs">
				<li class="'; if ($_GET['go']=="list" or empty($_GET['go'])){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_bnkmngr&go=list">'.$LANG['tablist'].'</a></li>
				<li class="'; if ($_GET['go']=="search"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_bnkmngr&go=search">'.$LANG['tabsearch'].'</a></li>
				<li class="'; if ($_GET['go']=="manage"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_bnkmngr&go=manage">'.$LANG['tabmanage'].'</a></li>
				<li class="'; if ($_GET['go']=="setting"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_bnkmngr&go=setting">'.$LANG['tabsetting'].'</a></li>
			</ul>
		</div>
		<div id="tab0box" class="tabbox tab-content admin-tabs">
			<div id="tab_content" class="tab-pane active" style="text-align:right;">';

	// tabscontent
	// list
	if ($_GET['go']=="list" or empty($_GET['go'])){

		// remove bill
		if ($_GET['go']=="list" AND isset($_GET['remove'])){
				mysql_query("DELETE FROM `mod_venon_bnkmngr_bills` WHERE id=$_GET[remove]");
		}

		if($_GET['order'] != null) {$order = $_GET['order']; $_SESSION['fishorder']= $_GET['order'];} else {$order = 'id';}
		if($_GET['sort'] != null) {$sort = $_GET['sort']; $_SESSION['fishsort']= $_GET['sort'];} else {$sort = 'DESC';}
		if($_GET['limit'] != null) {$limit = $_GET['limit']; $_SESSION['fishlimit']= $_GET['limit'];} else {$limit = '25';}
		if($_GET['offset'] != null) {$offset = $_GET['offset']; $_SESSION['fishoffset']= $_GET['offset'];} else {$offset = '0';}

		//reset session
		if (isset($_GET['reset'])){unset($_SESSION['fishorder'],$_SESSION['fishsort'],$_SESSION['fishlimit'],$_SESSION['fishoffset']);}

		if (isset($_SESSION['fishorder'])){$order = $_SESSION['fishorder'];}
		if (isset($_SESSION['fishsort'])){$sort = $_SESSION['fishsort'];}
		if (isset($_SESSION['fishlimit'])){$limit = $_SESSION['fishlimit'];}
		if (isset($_SESSION['fishoffset'])){$offset = $_SESSION['fishoffset'];}

		$pageNum = 1;
		if(isset($_GET['page'])){$pageNum = $_GET['page'];}
		if (isset($_GET['page'])) {$pgadrss = '&page='.$_GET['page'];}

		// counting the offset
		$offset = ($pageNum - 1) * $limit;

		// get setting
		$result = mysql_query('SELECT * FROM `mod_venon_bnkmngr` WHERE setting= "options"');
		$setting = mysql_fetch_array($result);
		$jdate = $setting['jdate'];

		$result = mysql_query("SELECT * FROM `mod_venon_bnkmngr_bills` ORDER BY  `mod_venon_bnkmngr_bills`.`$order` $sort LIMIT $offset, $limit");

		$count = mysql_query("SELECT * FROM `mod_venon_bnkmngr_bills` ORDER BY  `mod_venon_bnkmngr_bills`.`id`");
		$counter = mysql_num_rows($count);

		$nextpage = $pageNum +1;
		$prepage = $pageNum -1;
		$ifnext = $counter/($limit*$pageNum);

			echo '
			<script type="text/javascript">

				function doDeleteBill(id) {
				if (confirm("'.$LANG['catbill'].'")) {
				window.location="addonmodules.php?module=v_bnkmngr&go=list&remove="+id+"";
				}}
				</script>';

		echo '<form method="GET" action="addonmodules.php">
			'.$LANG['sortting'].'
			<input name="module" type="hidden" value="v_bnkmngr"/>
			<input name="go" type="hidden" value="list"/>
			<select name="order">
				<option value="id">'.$LANG['id'].'</option>
				<option value="reluserid">'.$LANG['reluser'].'</option>
				<option value="relbank">'.$LANG['relbank'].'</option>
				<option value="paymenttype">'.$LANG['paymenttype'].'</option>
				<option value="amount">'.$LANG['amount'].'</option>
			</select>
			<select name="sort">
				<option value="DESC">'.$LANG['DESC'].'</option>
				<option value="ASC">'.$LANG['ASC'].'</option>
			</select>
			<select name="limit">
				<option value="25">25 '.$LANG['record'].'</option>
				<option value="50">50 '.$LANG['record'].'</option>
				<option value="100">100 '.$LANG['record'].'</option>
				<option value="200">200 '.$LANG['record'].'</option>
			</select>
			<input type="submit" value="'.$LANG['submit'].'" class="btn btn-small btn-info"/>
			<a class="btn btn-small btn-default" href="addonmodules.php?module=v_bnkmngr&go=list'.$pgadrss.'&reset">'.$LANG['mngdefault'].'</a>
		</form>';
		if ($counter > $limit AND $pageNum > 1) {echo '<a href="addonmodules.php?module=v_bnkmngr&page='.$prepage.'">'.$LANG['previous'].'</a>';}
		if ($counter > $limit AND $ifnext > 1) {echo '<a style="float:left;" href="addonmodules.php?module=v_bnkmngr&page='.$nextpage.'">'.$LANG['next'].'</a>';}
		echo '<br/><hr />
		<table width="100%">
			<tbody>
				<tr>
					<td>
						<table width="100%" class="form">
						<tbody><tr><td colspan="2" class="fieldarea" style="text-align:center;"><strong>'.$LANG['mpagelist'].'</strong></td></tr>
						<tr><td align="center">
						<div class="tablebg">
						<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
						<tbody>
							<tr>
								<th>'.$LANG['id'].'</th>
								<th>'.$LANG['date'].'</th>';
								if ($jdate =1) {echo '<th>'.$LANG['jdateth'].'</th>';}
								echo'<th>'.$LANG['reluser'].'</th>
								<th>'.$LANG['relbank'].'</th>
								<th>'.$LANG['paymenttype'].'</th>
								<th>'.$LANG['amount'].'</th>
								<th>'.$LANG['status'].'</th>
								<th width="20"></th>
								<th width="20"></th>
							</tr>';

						while ($data = mysql_fetch_array($result)) {
						print '<tr><td><a href="addonmodules.php?module=v_bnkmngr&go=edit&id='.$data['id'] .'" target="_blank">'.$data['id'] .'</a></td>
							<td>'. fromMySQLDate($data['date'],$data['date']) .'</td>';

						if ($jdate =1) {
							if (!function_exists('jdate')) {
								include_once(__DIR__.'/jdf.php');
							}
							echo '<td>'. jdate('l, j F Y',strtotime($data['date'])) .'</td>';
						}

						//user list
						$username = select_query('tblclients','firstname,lastname',array('id'=>$data['reluserid']));
						$userfetch = mysql_fetch_array($username);
						$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];
						echo'<td><a href="./clientssummary.php?userid='.$data[reluserid].'" title="'.$LANG['viewclient'].'" target="_blank">'. $userfullname .'</a></td>';

						if ($data['status']=='sent') {
							$status = '<td style="background:#d7d7d7;">'.$LANG['sent'].'</td>';
						} elseif ($data['status']=='fake') {
							$status = '<td style="background:rgb(248, 129, 129);">'.$LANG['fake'].'</td>';
						} elseif ($data['status']=='active') {
							$status = '<td style="background:rgb(157, 204, 151);">'.$LANG['active'].'</td>';
						}

						echo '<td>'.$data['relbank'].'</td>
							<td>'. $data['paymenttype'] .'</td>
							<td>'. formatCurrency($data['amount']) .'</td>
							'. $status .'
							<td><a href="addonmodules.php?module=v_bnkmngr&go=edit&id='. $data['id'] .'"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit" title="'.$LANG['edit'].'"></a></td><td>
							<a href="#" onclick="doDeleteBill('.$data[id].')"><img src="images/delete.gif" align="absmiddle" border="0" alt="'.$LANG['delete'].'"></a></td></tr>'
							;}

						$num_rows = mysql_num_rows($result);
						if ($num_rows == 0) {
						echo '<tr><td colspan="9">'.$LANG['nothingfound'].'</td></tr>';}
						echo'</tbody></table>
						</div>
						</td></tr></tbody></table>
					</td>
				</tr>
			</tbody>
		</table>';
		if ($counter > $limit AND $pageNum > 1) {echo '<a href="addonmodules.php?module=v_bnkmngr&page='.$prepage.'">'.$LANG['previous'].'</a>';}
		if ($counter > $limit AND $ifnext > 1) {echo '<a style="float:left;" href="addonmodules.php?module=v_bnkmngr&page='.$nextpage.'">'.$LANG['next'].'</a>';}
		echo '<br/><hr />
		<form method="GET" action="addonmodules.php">
			'.$LANG['sortting'].'
			<input name="module" type="hidden" value="v_bnkmngr"/>
			<input name="go" type="hidden" value="list"/>
			<select name="order">
				<option value="id">'.$LANG['id'].'</option>
				<option value="reluserid">'.$LANG['reluser'].'</option>
				<option value="relbank">'.$LANG['relbank'].'</option>
				<option value="paymenttype">'.$LANG['paymenttype'].'</option>
				<option value="amount">'.$LANG['amount'].'</option>
			</select>
			<select name="sort">
				<option value="DESC">'.$LANG['DESC'].'</option>
				<option value="ASC">'.$LANG['ASC'].'</option>
			</select>
			<select name="limit">
				<option value="25">25 '.$LANG['record'].'</option>
				<option value="50">50 '.$LANG['record'].'</option>
				<option value="100">100 '.$LANG['record'].'</option>
				<option value="200">200 '.$LANG['record'].'</option>
			</select>
			<input type="submit" value="'.$LANG['submit'].'" class="btn btn-small btn-info"/>
			<a class="btn btn-small btn-default" href="addonmodules.php?module=v_bnkmngr&go=list'.$pgadrss.'&reset">'.$LANG['mngdefault'].'</a>
		</form>';
	}
	// edit area
	$getid = $_GET['id'];
	$getreluserid = $_POST['reluserid'];
	$getamount = $_POST['amount'];
	$getrelinvoice = $_POST['relinvoice'];
	if ($_POST['status'] !== '') {$getstatus = $_POST['status'];} else {$getstatus = $_POST['orgstatus'];}
	$getpeygirinum = $_POST['peygirinum'];
	$getfishnum = $_POST['fishnum'];
	if ($_POST['paymenttype'] !== '') {$getpaymenttype = $_POST['paymenttype'];} else {$getpaymenttype = $_POST['orgpaymenttype'];}
	if ($_POST['relbank'] !== '') {$getrelbank = $_POST['relbank'];} else {$getrelbank = $_POST['orgrelbank'];}
	$getdesc = $_POST['desc'];
	$transection = 'bnkmngr-'.$getpeygirinum.'/'.$getfishnum;
	$getfishdate = $_POST['date'];

	// get admin user
	$aid = $_SESSION['adminid'];
	$result = select_query('tbladmins', 'username',array("id"=>$aid));
	$data = mysql_fetch_array($result);

	// save fish
	if ($_GET['go']=="edit" AND $_GET['act']=="save" AND isset($_POST['amount'])){
		$update = array("amount"=>"$getamount", "date"=>toMySQLDate($getfishdate), "relinvoice"=>"$getrelinvoice", "status"=>"$getstatus", "peygirinum"=>"$getpeygirinum", "fishnum"=>"$getfishnum", "paymenttype"=>"$getpaymenttype", "relbank"=>"$getrelbank", "desc"=>"$getdesc");
		$where = array("id"=>"$getid");
		update_query('mod_venon_bnkmngr_bills',$update,$where);
			// add transection
			if ($_POST['status'] == 'active' AND $_POST['orgstatus'] !== 'active') {

				if (empty($getrelinvoice) OR $getrelinvoice =='') {
					//apply to credit
					 $command = "addcredit";
					 $adminuser = $data['username'];
					 $values["clientid"] = $getreluserid;
					 $values["description"] = $LANG['creditdesc'];
					 $values["amount"] = $getamount;

					 $localAPI = localAPI($command,$values,$adminuser);
					 if ($localAPI['result'] == 'success') {$paysuccess = '<br/><p>'.$LANG['creditnotifiction'].'</p>'.$localAPI['newbalance'];}
				} else {
					//apply to invoice
					$command = "addinvoicepayment";
					$adminuser = $data['username'];
					$values["invoiceid"] = $getrelinvoice;
					$values["transid"] = "$transection";
					$values["amount"] = "$getamount";
					$values["gateway"] = "bnkmngr";

					$localAPI = localAPI($command,$values,$adminuser);
					if ($localAPI['result'] == 'success') {$paysuccess = '<br/><p>'.$LANG['fishaddtrans'].'</p>';}
				}

			}
		$command = "logactivity";
		$values["description"] = "Venon bnkmngr - Edit Bill #$getid";
		$localAPI = localAPI($command,$values,$adminuser);

		echo '<div class="infobox"><strong><span class="title">'.$LANG['fishsavesuc'].'</span></strong>'.$paysuccess.'</div>';
	}

	// fish details
	$result = select_query('mod_venon_bnkmngr_bills','*',array('id'=>$_GET['id']));
	$fish = mysql_fetch_array($result);
	if ($_GET['go']=="edit"){
		//user list
		$username = select_query('tblclients','firstname,lastname',array('id'=>$fish['reluserid']));
		$userfetch = mysql_fetch_array($username);
		$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];

		echo'<p>'.$LANG['editfishnote'].'</p>
		<form method="post" action="addonmodules.php?module=v_bnkmngr&go=edit&id='.$_GET[id].'&act=save">
		<table class="form" name="editfish" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tbody>
				<tr>
					<td class="fieldlabel">'.$LANG['reluser'].'</td>
					<td class="fieldarea"><input disabled="disabled" type="text" value="'.$userfullname.'" /></td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['sentdate'].'</td>
					<td class="fieldarea"><input type="text" name="date" value="'.fromMySQLDate($fish['date'],$fish['date']).'" />';

					if ($jdate =1) {
						if (!function_exists('jdate')) {
							include_once(__DIR__.'/jdf.php');
						}
						echo '<span> '. jdate('l, j F Y',strtotime($fish['date'])) .'</span>';
					}

					echo'
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['amount'].'</td>
					<td class="fieldarea"><input type="text" name="amount" value="'.$fish['amount'].'" />'. formatCurrency($fish['amount']).'</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['relinvoice'].'</td>
					<td class="fieldarea"><input name="relinvoice" type="text" value="'.$fish['relinvoice'].'" />';
					if($fish['relinvoice'] >1){ echo '<a href="invoices.php?action=edit&id='.$fish['relinvoice'].'" target="_blank"> ('.$LANG['viewinvoice'].')</a>';}
					echo'</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['status'].'</td>
					<td class="fieldarea">
						<input disabled="disabled" name="viewstat" type="text" value="';
							if($fish['status']=='sent'){echo $LANG['sent'];}
							if($fish['status']=='fake'){echo $LANG['fake'];}
							if($fish['status']=='active'){echo $LANG['active'];}
						echo'"/>
						<input name="orgstatus" type="hidden" value="';
							if($fish['status']=='sent'){echo 'sent';}
							if($fish['status']=='fake'){echo 'fake';}
							if($fish['status']=='active'){echo 'active';}
						echo'"/>
						<select name="status">
							<option></option>
							<option value="sent">'.$LANG['sent'].'</option>
							<option value="fake">'.$LANG['fake'].'</option>
							<option value="active">'.$LANG['active'].'</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['peygirinum'].'</td>
					<td class="fieldarea"><input type="text" name="peygirinum" value="'.$fish['peygirinum'].'" /></td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['fishnum'].'</td>
					<td class="fieldarea"><input type="text" name="fishnum" value="'.$fish['fishnum'].'" /></td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['paymenttype'].'</td>
					<td class="fieldarea"><input disabled="disabled" type="text" value="'.$fish['paymenttype'].'" />
					<input name="orgpaymenttype" type="hidden" value="'.$fish['paymenttype'].'" />
					<select name="paymenttype">
						<option></option>
						<option value="'.$LANG['payment1'].'">'.$LANG['payment1'].'</option>
						<option value="'.$LANG['payment2'].'">'.$LANG['payment2'].'</option>
						<option value="'.$LANG['payment3'].'">'.$LANG['payment3'].'</option>
						<option value="'.$LANG['payment4'].'">'.$LANG['payment4'].'</option>
						<option value="'.$LANG['payment5'].'">'.$LANG['payment5'].'</option>
						<option value="'.$LANG['payment6'].'">'.$LANG['payment6'].'</option>
					</select>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['relbank'].'</td>
					<td class="fieldarea"><input disabled="disabled" type="text" value="'.$fish['relbank'].'" />
					<input name="orgrelbank" type="hidden" value="'.$fish['relbank'].'" />';
					// bank menu
					echo '<select name="relbank"><option></option>';
					$result = select_query('mod_venon_bnkmngr_banks','bankname',array('enable'=>1));
					while ($banks = mysql_fetch_array($result)) {
						echo '<option value="'.$banks['bankname'].'">'.$banks['bankname'].'</option>';
					}
					echo '</select>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['fishdesc'].'</td>
					<td class="fieldarea">
						<textarea name="desc" style="width: 90%; height:auto;">'.$fish['desc'].'</textarea>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">'.$LANG['fishattach'].'</td>
					<td class="fieldarea">';if(isset($fish['attach']) and $fish['attach'] !== ''){echo '<a href="../'.$fish['attach'].'" target="_blank">'.$LANG['fishattachview'].'</a>';} else {echo $LANG['fishnoattach'];}
					echo'</td>
				</tr>
			</tbody>
		</table>
		<input name="reluserid" type="hidden" value="'.$fish['reluserid'].'"/>
		<p style="text-align:center;"><input class="btn btn-success" type="submit" value="'.$LANG['fishsave'].'"><a class="btn btn-default" style="margin:0 1px;" href="addonmodules.php?module=v_bnkmngr&go=list">'.$LANG['fishback'].'</a></p>
		</form>
		';
	}
	// search
	if ($_GET['go']=="search"){
		// search where
		function bnkquery_oprator ($value) {
			switch ($value) {
				case "equal": $return = '=';
				break;
				case "bigger": $return = '>=';
				break;
				case "smaller": $return = '<=';
				break;
			}
			return $return;
		}

		$where = $_SESSION['bnkmngrsearch'];
		if (!empty($_POST[idvalue])){$where = 'id'. bnkquery_oprator($_POST[idOperator]) .$_POST[idvalue];}
		if (!empty($_POST[reluservalue])){$where = 'reluserid =' .$_POST[reluservalue];}
		if (!empty($_POST[relbankvalue])){$where = "relbank = '$_POST[relbankvalue]'";}
		if (!empty($_POST[amountvalue])){$where = 'amount'. bnkquery_oprator($_POST[amountOperator]) .$_POST[amountvalue];}
		if (!empty($_POST[relinvoicevalue])){$where = "relinvoice = '$_POST[relinvoicevalue]'";}
		if (!empty($_POST[paymenttype])){$where = "paymenttype = '$_POST[paymenttype]'";}
		if (!empty($_POST[peygirinumvalue])){$where = "peygirinum = '$_POST[peygirinumvalue]'";}

		$_SESSION['bnkmngrsearch'] = $where;

		echo '<p>'.$LANG['searchintro'].'</p>
		<form method="POST" action="addonmodules.php?module=v_bnkmngr&go=search">
			<input name="module" type="hidden" value="v_bnkmngr"/>
			<input name="go" type="hidden" value="search"/>
			<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3"><tbody>
			<tr>
				<td class="fieldlabel" width="20%">'.$LANG['id'].'</td>
				<td class="fieldarea" width="10%">
					<select name="idOperator">
						<option value="equal">'.$LANG['equal'].'</option>
						<option value="bigger">'.$LANG['bigger'].'</option>
						<option value="smaller">'.$LANG['smaller'].'</option>
					</select>
				</td>
				<td class="fieldlabel"><input type="text" name="idvalue" /></td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['reluserid'].'</td>
				<td class="fieldarea" width="10%"></td>
				<td class="fieldlabel"><input type="text" name="reluservalue" /></td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['relbank'].'</td>
				<td class="fieldarea" width="10%"></td>
				<td class="fieldlabel"><select name="relbankvalue"><option></option>';
				$result = mysql_query('SELECT * FROM `mod_venon_bnkmngr_banks` WHERE enable= 1 ORDER BY  `mod_venon_bnkmngr_banks`.`displayorder` ASC');
				while ($data = mysql_fetch_array($result)) {
					echo '<option value="'.$data['bankname'].'">'.$data['bankname'].'</option>';
				}
			echo '</select>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['amount'].'</td>
				<td class="fieldarea" width="10%">
					<select name="amountOperator">
						<option value="equal">'.$LANG['equal'].'</option>
						<option value="bigger">'.$LANG['bigger'].'</option>
						<option value="smaller">'.$LANG['smaller'].'</option>
					</select>
				</td>
				<td class="fieldlabel"><input type="text" name="amountvalue" /></td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['relinvoice'].'</td>
				<td class="fieldarea" width="10%"></td>
				<td class="fieldlabel"><input type="text" name="relinvoicevalue" /></td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['paymenttype'].'</td>
				<td class="fieldarea" width="10%"></td>
				<td class="fieldlabel">
					<select name="paymenttype">
						<option></option>
						<option value="'.$LANG['payment1'].'">'.$LANG['payment1'].'</option>
						<option value="'.$LANG['payment2'].'">'.$LANG['payment2'].'</option>
						<option value="'.$LANG['payment3'].'">'.$LANG['payment3'].'</option>
						<option value="'.$LANG['payment4'].'">'.$LANG['payment4'].'</option>
						<option value="'.$LANG['payment5'].'">'.$LANG['payment5'].'</option>
						<option value="'.$LANG['payment6'].'">'.$LANG['payment6'].'</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><strong>'.$LANG['or'].'</strong> '.$LANG['peygirinum'].'</td>
				<td class="fieldarea" width="10%"></td>
				<td class="fieldlabel"><input type="text" name="peygirinumvalue" /></td>
			</tr>
			<tr>
				<td class="fieldlabel" width="20%"><input class="btn btn-default" type="submit" value="'.$LANG['submit'].'"/></td>
				<td class="fieldlabel" width="10%"></td>
				<td class="fieldlabel"></td>
			</tr>
			</tbody></table>
		</form>';
		if($_GET['order'] != null) {$order = $_GET['order'];} else {$order = 'id';}
		if($_GET['sort'] != null) {$sort = $_GET['sort'];} else {$sort = 'DESC';}
		if($_GET['limit'] != null) {$limit = $_GET['limit'];} else {$limit = '25';}
		if($_GET['offset'] != null) {$offset = $_GET['offset'];} else {$offset = '0';}
		$pageNum = 1;
		if(isset($_GET['page'])){$pageNum = $_GET['page'];}

		// counting the offset
		$offset = ($pageNum - 1) * $limit;
		$result = mysql_query("SELECT * FROM `mod_venon_bnkmngr_bills` WHERE $where ORDER BY  `mod_venon_bnkmngr_bills`.`$order` $sort LIMIT $offset, $limit");
		$counter = 0;
		$count = mysql_query("SELECT * FROM `mod_venon_bnkmngr_bills` WHERE $where ORDER BY  `mod_venon_bnkmngr_bills`.`id`");
		$counter = mysql_num_rows($count);

		$nextpage = $pageNum +1;
		$prepage = $pageNum -1;
		$ifnext = $counter/($limit*$pageNum);

		echo '<form method="GET" action="addonmodules.php?module=v_bnkmngr&go=search">
			'.$LANG['sortting'].'
			<input name="module" type="hidden" value="v_bnkmngr"/>
			<input name="go" type="hidden" value="search"/>
			<select name="order">
				<option value="id">'.$LANG['id'].'</option>
				<option value="reluserid">'.$LANG['reluser'].'</option>
				<option value="relbank">'.$LANG['relbank'].'</option>
				<option value="paymenttype">'.$LANG['paymenttype'].'</option>
				<option value="amount">'.$LANG['amount'].'</option>
			</select>
			<select name="sort">
				<option value="DESC">'.$LANG['DESC'].'</option>
				<option value="ASC">'.$LANG['ASC'].'</option>
			</select>
			<select name="limit">
				<option value="25">25 '.$LANG['record'].'</option>
				<option value="50">50 '.$LANG['record'].'</option>
				<option value="100">100 '.$LANG['record'].'</option>
				<option value="200">200 '.$LANG['record'].'</option>
			</select>
			<input type="submit" value="'.$LANG['submit'].'" class="btn btn-info"/>
		</form>';
		if ($counter > $limit AND $pageNum > 1) {echo '<a href="addonmodules.php?module=v_bnkmngr&go=search&page='.$prepage.'">'.$LANG['previous'].'</a>';}
		if ($counter > $limit AND $ifnext > 1) {echo '<a style="float:left;" href="addonmodules.php?module=v_bnkmngr&go=search&page='.$nextpage.'">'.$LANG['next'].'</a>';}
		echo '<br/><hr />';
		if ($counter>0){echo '<p>'.$LANG['searchresult'] . $counter.'</p>';}
		echo '<table width="100%">
			<tbody>
				<tr>
					<td>
						<table width="100%" class="form">
						<tbody><tr><td colspan="2" class="fieldarea" style="text-align:center;"><strong>'.$LANG['mpagelist'].'</strong></td></tr>
						<tr><td align="center">
						<div class="tablebg">
						<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
						<tbody><tr><th>'.$LANG['id'].'</th><th>'.$LANG['date'].'</th><th>'.$LANG['reluser'].'</th><th>'.$LANG['relbank'].'</th><th>'.$LANG['paymenttype'].'</th><th>'.$LANG['amount'].'</th><th>'.$LANG['status'].'</th><th width="20"></th></tr>';

						while ($data = mysql_fetch_array($result)) {
						print '<tr><td><a href="" target="_blank">'.$data['id'] .'</a></td>
							<td>'. fromMySQLDate($data['date'],$data['date']) .'</td>';

						//user list
						$username = select_query('tblclients','firstname,lastname',array('id'=>$data['reluserid']));
						$userfetch = mysql_fetch_array($username);
						$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];
						echo'<td><a href="./clientssummary.php?userid='.$data[reluserid].'" title="'.$LANG['viewclient'].'" target="_blank">'. $userfullname .'</a></td>';

						if ($data['status']=='sent') {
							$status = '<td style="background:#d7d7d7;">'.$LANG['sent'].'</td>';
						} elseif ($data['status']=='fake') {
							$status = '<td style="background:rgb(248, 129, 129);">'.$LANG['fake'].'</td>';
						} elseif ($data['status']=='active') {
							$status = '<td style="background:rgb(157, 204, 151);">'.$LANG['active'].'</td>';
						}

						echo '<td>'.$data['relbank'].'</td>
							<td>'. $data['paymenttype'] .'</td>
							<td>'. $data['amount'] .'</td>
							'. $status .'
							<td><a href="addonmodules.php?module=v_bnkmngr&go=edit&id='. $data['id'] .'" target="_blank"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit" title="'.$LANG['edit'].'"></a></td></tr>'
							;}

						$num_rows = mysql_num_rows($result);
						if ($num_rows == 0) {
						echo '<tr><td colspan="9">'.$LANG['nothingfound'].'</td></tr>';}
						echo'</tbody></table>
						</div>
						</td></tr></tbody></table>
					</td>
				</tr>
			</tbody>
		</table>';
		if ($counter > $limit AND $pageNum > 1) {echo '<a href="addonmodules.php?module=v_bnkmngr&go=search&page='.$prepage.'">'.$LANG['previous'].'</a>';}
		if ($counter > $limit AND $ifnext > 1) {echo '<a style="float:left;" href="addonmodules.php?module=v_bnkmngr&go=search&page='.$nextpage.'">'.$LANG['next'].'</a>';}
		echo '<br/><hr />
		<form method="GET" action="addonmodules.php?module=v_bnkmngr&go=search">
			'.$LANG['sortting'].'
			<input name="module" type="hidden" value="v_bnkmngr"/>
			<input name="go" type="hidden" value="search"/>
			<select name="order">
				<option value="id">'.$LANG['id'].'</option>
				<option value="reluserid">'.$LANG['reluser'].'</option>
				<option value="relbank">'.$LANG['relbank'].'</option>
				<option value="paymenttype">'.$LANG['paymenttype'].'</option>
				<option value="amount">'.$LANG['amount'].'</option>
			</select>
			<select name="sort">
				<option value="DESC">'.$LANG['DESC'].'</option>
				<option value="ASC">'.$LANG['ASC'].'</option>
			</select>
			<select name="limit">
				<option value="25">25 '.$LANG['record'].'</option>
				<option value="50">50 '.$LANG['record'].'</option>
				<option value="100">100 '.$LANG['record'].'</option>
				<option value="200">200 '.$LANG['record'].'</option>
			</select>
			<input type="submit" value="'.$LANG['submit'].'" class="btn btn-info"/>
		</form>';
	}
	// manage
	if ($_GET['go']=="manage"){
		//save single
		if (isset($_POST['bankname'])) {
			$table = "mod_venon_bnkmngr_banks";
			$update = array("bankname"=>$_POST['bankname'], "accountnumber"=>$_POST['accountnumber'], "cartnumber"=>$_POST['cartnumber'], "shabanumber"=>$_POST['shabanumber'], "enable"=>$_POST['enable'], "accountholder"=>$_POST['accountholder'], "displayorder"=>$_POST['displayorder']);
			$where = array("id"=>$_POST['id']);
			update_query($table,$update,$where);
		}
		// get banks
		$result = select_query('mod_venon_bnkmngr_banks','*','','displayorder','ASC');
		while ($data = mysql_fetch_array($result)) {
			echo '<form method="POST" action="addonmodules.php?module=v_bnkmngr&go=manage">
			<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3"><tbody>
			<input type="hidden" name="id" size="30" value="'.$data['id'].'"/>
			<tr><td class="fieldlabel" width="20%">'.$LANG['bankname'].'</td><td class="fieldarea"><img style="float:right; padding: 0 0 0 10px;" src="../'.$data['banklogo'].'" /><input type="text" style="margin-top:15px;" name="bankname" size="30" value="'.$data['bankname'].'"/></td><td class="fieldlabel" width="20%">'.$LANG['accountnumber'].'</td><td class="fieldarea"><input style="direction:ltr; text-align:left;" type="text" name="accountnumber" size="30" value="'.$data['accountnumber'].'"/></td></tr>
			<tr><td class="fieldlabel" width="20%">'.$LANG['cartnumber'].'</td><td class="fieldarea"><input style="direction:ltr; text-align:left;" type="text" name="cartnumber" size="30" value="'.$data['cartnumber'].'"/></td><td class="fieldlabel" width="20%">'.$LANG['shabanumber'].'</td><td class="fieldarea"><input style="direction:ltr; text-align:left;" type="text" name="shabanumber" size="30" value="'.$data['shabanumber'].'"/></td></tr>
			<tr><td class="fieldlabel">'.$LANG['bankenable'].'</td><td class="fieldarea"><label><input type="radio" name="enable" value="1"'; if ($data['enable'] == '1') {echo 'checked';} echo '/>'.$LANG['yes'].'</label><label><input type="radio" name="enable" value="0" '; if ($data['enable'] == '0') {echo 'checked';} echo ' />'.$LANG['no'].'</label></td><td class="fieldlabel" width="20%">'.$LANG['accountholder'].'</td><td class="fieldarea"><input type="text" name="accountholder" size="30" value="'.$data['accountholder'].'"/></td></tr><tr><td class="fieldlabel" width="20%">'.$LANG['displayorder'].'</td><td class="fieldarea"><input style="direction:ltr; text-align:left;" type="text" name="displayorder" size="5" value="'.$data['displayorder'].'"/></td><td style="text-align:cenetr;"><input type="submit" value="'.$LANG['submitt'].'" class="btn btn-primary" ></td></tr></tbody></table></form><br/>';
		}
	}
	// setting
	if ($_GET['go']=="setting"){
		if (isset($_POST['attach'])) {
			$table = "mod_venon_bnkmngr";
			$update = array("attach"=>$_POST['attach'], "attachformat"=>$_POST['attachformat'], "attachmaxsize"=>$_POST['attachmaxsize'], "jdate"=>$_POST['jdate'], );
			$where = array("setting"=>"options");
			update_query($table,$update,$where);
		}

		// get setting

		echo '<form method="post" action="addonmodules.php?module=v_bnkmngr&go=setting">
		<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tr><td class="fieldlabel">'.$LANG['jdate'].'</td><td class="fieldarea"><label><input type="radio" name="jdate" value="1" '; if ($data['jdate'] == '1') {echo 'checked';} echo ' />'.$LANG['yes'].'</label><label><input type="radio" name="jdate" value="0" '; if ($data['jdate'] == '0') {echo 'checked';} echo ' />'.$LANG['no'].'</label></td></tr>
			<tr><td class="fieldlabel">'.$LANG['attach'].'</td><td class="fieldarea"><label><input type="radio" name="attach" value="1" '; if ($data['attach'] == '1') {echo 'checked';} echo ' />'.$LANG['yes'].'</label><label><input type="radio" name="attach" value="0" ';
			if ($data['attach'] == '0') {echo 'checked';} echo ' />'.$LANG['no'].'</label></td></tr>
			<tr><td class="fieldlabel">'.$LANG['attachmaxsize'].'</td><td class="fieldarea"><label><input type="text" name="attachmaxsize" value="'.$data['attachmaxsize'].'"/></td></tr>
		</table>
		</table>
		<p align="center"><input type="submit" value="'.$LANG['submitt'].'" class="btn btn-primary" ></p>
		</form>
		';
	}

	//end of div
	echo'</div></div>';

}

function v_bnkmngr_sidebar($vars) {
    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $option1 = $vars['option1'];
    $option2 = $vars['option2'];
    $option3 = $vars['option3'];
    $option4 = $vars['option4'];
    $option5 = $vars['option5'];
    $LANG = $vars['_lang'];

    $sidebar = '<span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" /> Venon Bank Manager</span>
<ul class="menu">
        <li><a href="./addonmodules.php?module=v_bnkmngr&go=list">'.$LANG['tablist'].'</a></li>
		<li><a href="./addonmodules.php?module=v_bnkmngr&go=search">'.$LANG['tabsearch'].'</a></li>
		<li><a href="./addonmodules.php?module=v_bnkmngr&go=manage">'.$LANG['tabmanage'].'</a></li>
		<li><a href="./addonmodules.php?module=v_bnkmngr&go=setting">'.$LANG['tabsetting'].'</a></li>
        <li><a href="#">'.$LANG['bnkmngrrversion'].': '.$version.'</a></li>
    </ul>';
    return $sidebar;

}

function v_bnkmngr_clientarea($vars) {

    $modulelink = $vars['modulelink'];
    $LANG = $vars['_lang'];
	$userid = $_SESSION['uid'];
	$todaysdate = date('Y/m/d G:i');

	$currencyData = getCurrency($userid);



	if (!function_exists('jdate')) {
		include_once(__DIR__.'/jdf.php');
	}

	$jy = jdate('Y',strtotime($todaysdate),'','',0);
	$jm = jdate('m',strtotime($todaysdate),'','',0);
	$jd = jdate('d',strtotime($todaysdate),'','',0);

	if($_GET['p']=='list'){
		$key = 1;
		$result = select_query('mod_venon_bnkmngr_bills','*',array('reluserid'=>$userid),'date','DESC');
		while ($bill = mysql_fetch_array($result)) {
			$bills[$key]['paymenttype'] = $bill['paymenttype'];
			$bills[$key]['fishnum'] = $bill['fishnum'];
			$bills[$key]['peygirinum'] = $bill['peygirinum'];
			$bills[$key]['amount'] = formatCurrency($bill['amount'], $currencyData);
			$bills[$key]['date'] = fromMySQLDate($bill['date'],$bill['date']);
			$bills[$key]['jdate'] = jdate('Y/m/d H:i',strtotime($bill['date']));
			$bills[$key]['status'] = $bill['status'];
			$key = $key+1;
		}
	} else {
		$key = 1;
		$result = select_query('mod_venon_bnkmngr_banks','*',array('enable'=>1),'displayorder','ASC');
		while ($bank = mysql_fetch_array($result)) {
			$banks[$key]['banklogo'] = $bank['banklogo'];
			$banks[$key]['bankname'] = $bank['bankname'];
			$banks[$key]['accountnumber'] = $bank['accountnumber'];
			$banks[$key]['cartnumber'] = $bank['cartnumber'];
			$banks[$key]['shabanumber'] = $bank['shabanumber'];
			$banks[$key]['accountholder'] = $bank['accountholder'];
			$key = $key+1;
		}

		//get unpaid invoices
		$key = 1;
		$result = select_query('tblinvoices','id',array('status'=>'unpaid','userid'=>$userid));
		while ($invoice = mysql_fetch_array($result)) {
			$invoices[$key]['id'] = $invoice['id'];
			$key = $key+1;
		}
	}


	// get setting
	$result= select_query('mod_venon_bnkmngr','*',array('setting'=>'options'));
	$data = mysql_fetch_array($result);
	$attach = $data['attach'];
	$jdate = $data['jdate'];

    return array(
        'pagetitle' => $LANG['clienttitle'],
        'breadcrumb' => array('index.php?m=v_bnkmngr'=>$LANG[clienttitle]),
        'templatefile' => 'bnkmain',
        'requirelogin' => false, # or false
        'vars' => array(
            'bills' => $bills,
			'banks' => $banks,
			'invoices' => $invoices,
            'chkamount' => $chkamount,
			'jtodaysdate' => array('y'=>$jy, 'm'=>$jm, 'd'=>$jd),
			'attach' => $attach,
			'jdate' => $jdate,
        ),
    );
}
?>
