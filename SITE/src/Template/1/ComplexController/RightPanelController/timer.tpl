{* Smarty *}
{* Правое меню: Таймер *}
{* Refactored in 2015 *}

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
<div class="sideblock-block">
	<div class="sideblock-header">Осталось времени</div>
	<div class="sideblock-body">
		<p style="font-size:8pt;margin-right:0.1cm;margin-left:0.2cm;text-align:justify"><? echo $conf['time_text'] ?>: <center><div class="small" id="mycountdowndiv"></div><script language='JavaScript'>doCountdown();</script></center></p>
	</div>
</div>