<link href="modules/addons/v_bnkmngr/venon_bnkmngr_style.css" rel="stylesheet">
<a class="btn btn-small btn-default" href="./index.php?m=v_bnkmngr" style="float: left;"><i class="icon icon-list-alt"></i>مشاهده اطلاعات / ثبت فیش</a>
<h1>فیش های ثبت شده شما</h1>
{if $smarty.get.s eq 'success'}
<div class="alert-message success alert alert-block alert-success">
	<p><strong>اطلاعات ارسالی شما با موفقیت ثبت شد:</strong> اطلاعات پرداخت شما ثبت و به زودی توسط بخش حسابداری بررسی خواهد شد، اطلاعات فیش های ثبت شده شما در زیر لیست شده است. درصورتی که پرداخت دیگری انجام داده اید از طریق فرم مجددا اطلاعات جدید را ثبت نمایید.</p>
</div>
{/if}
{if $loggedin}
<br/>
<table class="table table-striped table-framed data">
    <thead>
        <tr>
            <th class="headerSortasc">تاریخ ثبت</th>
            <th>روش پرداخت</th>
            <th>شماره فیش</th>
			<th>شماره پی گیری</th>
			<th>میزان پرداخت شده</th>
            <th style="text-align:center;">وضعیت</th>
        </tr>
    </thead>
    <tbody>

{foreach from=$bills item=bill}
	<tr>
		<td><strong>{if $jdate}{$bill.jdate}{else}{$bill.date}{/if}</strong></td>
		<td>{$bill.paymenttype}</td>
		<td>{$bill.fishnum}</td>
		<td>{$bill.peygirinum}</td>
		<td>{$bill.amount}</td>
		<td><span class="label 
		{if $bill.status eq 'sent'}label-warning">معلق{elseif $bill.status eq 'fake'}label-default">رد شد{elseif $bill.status eq 'active'}label-success">تایید شد{/if}
		</span></td>
	</tr>
{/foreach}
       
    </tbody>
</table>
{else}
<div class="alert-message success alert alert-block alert-success">
	<p><strong>لطفا وارد شوید:</strong> جهت بررسی فیش های ثبت شده خود ابتدا باید وارد شوید.</p>
</div>
{/if}