<?php if($out['showForm'] != true ) {?>
<script language="JavaScript">
		function maxlength_lang(form){
		   var maxlength=210;
		   str=form.message.value;
		   dlina=str.length;
		   if(dlina>maxlength)form.message.value=str.substring(0,maxlength);
		   form.lang.value=(maxlength-dlina);}
		   required = new Array("subject", "message");
		   required_show = new Array("свой ник", "сообщение");
		function SendForm () {
		   var i, j;
		   for(j=0; j<required.length; j++) {
		      for (i=0; i<document.forms[0].length; i++) {
		         if (document.forms[0].elements[i].name == required[j] && document.forms[0].elements[i].value == "" ) {
		            alert('Вы не написали ' + required_show[j] + '');
		            document.forms[0].elements[i].focus();
		             return false;
		          }
		      }
		   }
		   return true;
		}
		</script>
<form name="sender" method="post" action="index.php?page=contact&type=<? echo $out['type']; ?>&send=go" onsubmit="return SendForm();" style='margin: 0pt'>
	<table align="center" border="0" cellspacing="1" cellpadding="0" width="98%">
		<tr>
			<td colspan=2 class=top height=18 align=center style='border: #323232 1px solid; background-color: #323232'><b>Написать автору сайта:</b> <? echo $out['typeList']; ?></td>
			<td class=top height=18 align=center style='border: #323232 1px solid; background-color: #323232'><b>Заполните все поля</b></td>
		</tr>
		<tr>
			<td class=top style="border: #323232 1px solid" height="18" align="center" style="background-color: #323232" width="35%">Ваше имя: <input class=textinput type="text" name="subject" size="25">
			</td>
			<td class=top style="border: #323232 1px solid" height="18" align="center" style="background-color: #323232" width="30%">Ваш e-mail: <input class=textinput type="text" name="mailfrom" size="25">
			</td>
			<td class=top style="border: #323232 1px solid" height="18" align="center" style="background-color: #323232" width="35%">Вам доступно <input class=textinput type="text" name="lang" size="3" value="210"> символов
			</td>
		</tr>
		<tr>
			<td colspan=3 class=fon style="border: #313131 1px solid" height="90" align="center" style="background-color: #323232" valign="center"><textarea class=textinput name="message" rows="4" cols="120" onChange="maxlength_lang(document.sender)" onKeyUp="maxlength_lang(document.sender)"
					onKeyDown="maxlength_lang(document.sender)" onClick="maxlength_lang(document.sender)" onKeyPress="maxlength_lang(document.sender)"></textarea><br> <input class=textinput type="submit" name="submit" style="width: 90pt; cursor: hand;" value="Отправить">&nbsp;<input class=textinput type="reset"
				name="reset" style="width: 90pt; cursor: hand;" value="Очистить"></td>
		</tr>
	</table>
</form>
<?php }?>