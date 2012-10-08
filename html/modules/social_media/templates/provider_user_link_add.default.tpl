<div class="SocialMedia">
	<h2><{$page_title}></h2>
<p>
<{"User authentication was successful. But could not find your account associated with the authentication information.
If you have an account on this site, you can associate your account and the authentication information with logging-in. 
Otherwise, please register by the form below. Your account will be associated with the authentication information."|t}>
</p>
	<div style="width:47%;float:left;">
		<form action="<{$xoops_url}>/user.php" method="post">
			<input name="op" type="hidden" value="login">
			<table class="outer">
				<tr>
					<th colspan="2"><{"If you have an account on this site"|t}>
</th>
				</tr>
				<tr>
					<td class="head"><{"User Name"|t}></td>
					<td class="odd"><input type="text" name="uname" /></td>
				</tr>
				<tr>
					<td class="head"><{"Password"|t}></td>
					<td class="even"><input type="password" name="pass" /></td>
				</tr>
			</table>
			<div style="text-align:center"><input type="submit" name="submit" value="<{"Login"|t}>" /></div>
		</form>
	</div>
	<div style="width:6%;float:left;">&nbsp</div>
	<div style="width:47%;float:left;">
		<{if $form->hasError()}>
			<ul>
				<{foreach from=$form->getErrors() item="error"}>
					<li><{$error|escape}></li>
				<{/foreach}>
			</ul>
		<{/if}>

		<form action="<{url controller='provider_user_link_add'}>" method="post">
			<table class="outer">
				<tr>
					<th colspan="2"><{"If you don't have an account on this site"|t}></th>
				</tr>
				<tr>
					<td class="head"><{"User Name"|t}></td>
					<td class="odd"><{form_input property=$form->property('uname')}></td>
				</tr>
				<tr>
					<td class="head"><{"Email"|t}></td>
					<td class="even"><{form_input property=$form->property('email')}></td>
				</tr>
			</table>
			<div style="text-align:center"><input type="submit" name="register" value="<{"register"|t}>" /></div>
		</form>
	</div>
	<div style="clear:both;">
	</div>
</div>
