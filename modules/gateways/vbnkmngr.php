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


function vbnkmngr_config() {
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"Venon bnkmngr"),
    );
	return $configarray;
}

function vbnkmngr_link($params) {

	# Invoice Variables
	$invoiceid = $params['invoiceid'];
	$description = $params["description"];
    $amount = $params['amount']; # Format: ##.##
    $currency = $params['currency']; # Currency Code

	# Enter your code submit to the gateway...
	$code = '<form method="POST" action="index.php?m=v_bnkmngr">
		<input type="hidden" name="description" value="'.$description.'" />
		<input type="hidden" name="invoiceid" value="'.$invoiceid.'" />
		<input type="hidden" name="amount" value="'.$amount.'" />
		<input type="submit" value="ثبت فیش بانکی / حساب های ما" />
	</form>';
	return $code;
}
?>
