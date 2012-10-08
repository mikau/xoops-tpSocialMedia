<div class="SocialMedia">
	<h2><{$page_title}></h2>
<{if $has_error == true}>
	<ul>
		<{foreach from=$errors item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
<{/if}>
<table id="jtable">
<tr>
<th width="20%"><{"Provider Name"|t}></th><th width="40%">Key</th><th width="40%">Secret</th>
</tr>
<tr>
<tbody id="jquery-ui-sortable">
	<{foreach item=provider from=$providerList name=loop}>
		<tr class="ui-state-default">
			<td class="td_name"><{$provider.name}><input type="hidden" class="id" name="id" value="<{$provider.id}>" size="30"></td>
			<td class="td_key"><input type="text" class="key" name="key" value="<{$provider.key}>" size="50"></td>
			<td class="td_secret"><input type="text" class="secret" name="secret" value="<{$provider.secret}>" size="50"></td>
		</tr>
	<{/foreach}>
</tbody>
</table>

<div style="margin-top:10px;">
<input type="button" id="submitSortable" value="<{"register"|t}>">
</div>
</div>

<script>
<!--
jQuery( function() {
	jQuery( '#jquery-ui-sortable' ) . sortable();
	jQuery( '#jquery-ui-sortable' ) . disableSelection();
	jQuery( '#submitSortable' ) . click( function() {
		var itemKeys = '';
		var itemSecrets = '';
		var itemIds = '';
		var values = '';
		var message =  "<{"are you sure? if fields of line are blank, user link information will be deleted."|t}>";
		var message_on = 0;

		var keys = new Array();
		var secrets = new Array();

		jQuery( '#jquery-ui-sortable .td_name' ) . map( function() {
			itemIds += jQuery( this ) .children( '.id' ) .attr('value') + ',';
		} );

		jQuery( '#jquery-ui-sortable .td_key' ) . map( function(n , i) {
			itemKeys += jQuery( this ) .children( '.key' ) .attr('value') + ',';
			keys[n] = jQuery( this ) .children( '.key' ) .attr('value');
		} );

		jQuery( '#jquery-ui-sortable .td_secret' ) . map( function(n , i) {
			itemSecrets += jQuery( this ) .children( '.secret' ) .attr('value') + ',';
			secrets[n] = jQuery( this ) .children( '.secret' ) .attr('value');
		} );


		// 空行があるとき、メッセージを出すためのチェック
		for (var i in keys) { 
			if ((keys[i] =='') && (secrets[i] == '')){
				message_on = 1;
			}
		}

		redirect_url = 'index.php?controller=provider_list&update=true&ids=' + itemIds + '&keys=' + itemKeys + '&secrets=' + itemSecrets;
		if(message_on == 1){
				if( confirm( message ) ){
					location . href = redirect_url;
				}
				} else {
					location . href = redirect_url;
				}

		}
	 );
} );


// -->
</script>



<style>
<!--
#jquery-ui-sortable {
	list-style-type: none;
	margin: 0;
	padding: 0;
	width: 70%;
}
#jquery-ui-sortable tr {
	margin: 0 3px 3px 3px;
	padding: 0.3em;
	padding-left: 1em;
	font-size: 15px;
	font-weight: bold;
	cursor: move;
}
-->
</style>

