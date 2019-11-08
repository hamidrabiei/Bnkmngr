{if $smarty.get.p eq 'list'}
	{include file="modules/addons/v_bnkmngr/list.tpl"}
{else}
	{include file="modules/addons/v_bnkmngr/bankmngr.tpl"}
{/if}