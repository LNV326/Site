{* Smarty *}
{* Правое меню: Таймер *}

{literal}
<SCRIPT language=JavaScript src='/js/modules/jscountdown.js'></SCRIPT>
<SCRIPT language=JavaScript>
var mycountdown = new Countdown();
with (mycountdown) {
{/literal}
  tagID = 'mycountdowndiv';
  setEventDate({$conf['event_time']});
  event = "{$conf['event_name']}";
  afterevent = "{$conf['event_after']}";
{literal}
}
addCountdown(mycountdown);
</SCRIPT>
{/literal}
<tr><td class="mtr">Осталось времени</td></tr>
<tr><td><p style="font-size:8pt;margin-right:0.1cm;margin-left:0.2cm;text-align:justify"><? echo $conf['time_text'] ?>: <center><div class="small" id="mycountdowndiv"></div><script language='JavaScript'>doCountdown();</script></center></p></td></tr>