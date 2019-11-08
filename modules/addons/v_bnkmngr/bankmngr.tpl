<link href="modules/addons/v_bnkmngr/venon_bnkmngr_style.css" rel="stylesheet">
<script src="modules/addons/v_bnkmngr/jquery.maskMoney.min.js" type="text/javascript"></script>

<script>{literal}
function dvalidate(value, min, max)
{
    if(parseInt(value) < min || isNaN(value))
        return min;
    else if(parseInt(value) > max)
        return max;
    else return value;
}

$(function() {
    $('#amount').maskMoney({thousands:',', precision:'0', allowZero:true});
  });
  {/literal}
</script>
<a class="btn btn-small btn-default" href="./index.php?m=v_bnkmngr&p=list" style="float: left;"><i class="icon icon-list-alt"></i> مشاهده فیش های ثبت شده</a>

<div class="page-header">
    <div class="styled_title"><h1>اطلاعات حساب های ما<small> در زیر می توانید مشخصات بانکی را مشاهده نمایید</small></h1></div>
</div>

<div class="row">
{foreach from=$banks item=bank}
	<div class="col-md-6 col-sm-12">
		<div class="bankbox">
			<img src="{$bank.banklogo}" />
				<div style="margin-right: 50px;">
					<p><strong>نام بانک: </strong>{$bank.bankname}</p>
					<p><strong>شماره حساب: </strong><kbd>{$bank.accountnumber}</kbd></p>
					<p><strong>شماره کارت: </strong><kbd>{$bank.cartnumber}</kbd></p>
				</div>
					<p><strong>شناسه شبا: </strong><kbd>{$bank.shabanumber}</kbd></p>
					<p><strong>به نام: </strong>{$bank.accountholder}</p>
		</div>
	</div>
{/foreach}
</div>

<br /><br />
{if $loggedin}
<div class="page-header">
    <div class="styled_title"><h1>ثبت مشخصات پرداخت<small> ثبت پرداخت های کارت به کارت، فیش بانکی، حواله های پایا و ساتنا.</small></h1></div>
</div>
<div class="alert-message warning alert alert-block alert-warning">
	<p><strong>توجه نمایید:</strong> لطفا اطلاعات فرم زیر را با دقت طبق فیش/حواله/پرداخت از طریق اینترنت بانک خود وارد نمایید، فیلدهای <strong>* دار</strong> ضروری می باشند.</p>
</div>


{if $smarty.get.s eq 'false'}
<div class="alert-message error alert alert-block alert-error">
	<p><strong>لطفا اطلاعات ضروری را پر نمایید:</strong>
		<ul>
			<li>میزان پرداخت</li>
			<li>شماره پی گیری</li>
		</ul>
	</p>
</div>
{/if}

{if $smarty.get.s eq 'e1'}
<div class="alert-message error alert alert-block alert-error">
	<p><strong>خطایی در هنگام آپلود عکس به وجود آمده است:</strong>
		<ul>
			<li>بررسی نمایید که تصویر شامل فرمت های مجاز می باشد.</li>
			<li>حجم تصویر پیوستی نباید بیش از یک مگابایت باشد.</li>
		</ul>
	</p>
</div>
{/if}

{if $smarty.get.s eq 'date'}
<div class="alert-message error alert alert-block alert-error">
	<p><strong>تاریخ وارد شده اشتباه می باشد:</strong> لطفا تاریخ پرداخت وارد شده خود به طور صحیح وارد نمایید.</p>
</div>
{/if}

<form method="POST" action="bnkmngr_submit.php" enctype="multipart/form-data">
	<fieldset>
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label for="date">تاریخ پرداخت (روز/ماه/سال)</label>
				<div class="row">
					<div class="col-xs-2">
						<input class="form-control" type="text" name="dated" value="{$jtodaysdate.d}" onkeyup="this.value = dvalidate(this.value, 1, 31)"/>
					</div>
					<div class="col-xs-1" style="max-width:5px;">/</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" name="datem" value="{$jtodaysdate.m}" onkeyup="this.value = dvalidate(this.value, 1, 12)"/>
					</div>
					<div class="col-xs-1" style="max-width:5px;">/</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" name="datey" value="{$jtodaysdate.y}" onblur="this.value = dvalidate(this.value, 1360, 1420)"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="relinvoice">فاکتور مرتبط</label>
				<div class="row">
					<div class="col-xs-4">
						<select class="form-control" name="relinvoice" id="relinvoice">
							<option></option>
						{foreach from=$invoices item=invoice name=invoice}
							<option {if $invoice.id eq $smarty.post.invoiceid}selected="selected"{/if}{if $invoice.id eq $smarty.get.invoiceid}selected="selected"{/if}>{$invoice.id}</option>
						{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="paymenttype">روش پرداخت</label>
				<div class="input">
					<select class="form-control" name="paymenttype" id="paymenttype">
						<option>کارت به کارت</option>
						<option>پرداخت حضوری (فیش)</option>
						<option>حواله پایا</option>
						<option>حواله ساتنا</option>
						<option>انتقال داخلی (اینترنت بانک)</option>
						<option>چک بانکی</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="peygirinum">شماره پی گیری (*)</label>
				<div class="input">
					<input class="form-control" type="text" name="peygirinum" id="peygirinum" value="{$smarty.post.peygirinum}{if $smarty.get.peygirinum}{$smarty.get.peygirinum}{/if}" />
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label for="amount">میزان پرداخت (*)</label>
				<div class="input">
					<input class="form-control" type="text" name="amount" id="amount" value="{if $smarty.post.amount}{$smarty.post.amount}{/if}{if $smarty.get.amount}{$smarty.get.amount}{/if}" />

				</div>
			</div>
			<div class="form-group">

				<label for="relbank">بانک مرتبط</label>
				<div class="input">
					<select class="form-control" name="relbank" id="relbank">
					{foreach from=$banks item=bank}
						<option>{$bank.bankname}</option>
					{/foreach}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="fishnum">شماره فیش/ حواله</label>
				<div class="input">
					<input class="form-control" type="text" name="fishnum" id="fishnum" />
				</div>
			</div>
			{if $attach eq 1}
			<div class="form-group">
				<label for="file">تصویر فیش/حواله (فرمت های مجاز: gif, png, jpg,  jpeg)</label>
				<div class="input">
					<input class="form-control" type="file" name="file" size="20" />
				</div>
			</div>
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label for="desc">توضیحات</label>
		<div class="input">
			<textarea class="form-control" name="desc" id="desc" rows="3" style="width:630px;">{$smarty.post.description}</textarea>
		</div>
	</div>
	<input type="hidden" name="reluserid" value="{$clientsdetails.id}" />
	<input type="hidden" name="status" value="sent" />
	</fieldset>
	<div class="actions">
		<input class="btn btn-primary" type="submit" name="save" value="ثبت" />
		<input class="btn btn-default" type="reset" value="لغو" />
	</div>
</form>
{else}
<div class="alert-message error alert alert-block alert-error">
	<p><strong>لطفا وارد شوید:</strong> در صورتی که قصد ثبت مشخصات پرداخت خود را دارید ابتدا وارد سایت شده و سپس مشخصات پرداخت را در فرم نمایش داده شده وارد نمایید.</p>
</div>
{/if}
