<p class=normal>
	<b>Команды для чата:</b><br> <u><i>/exit</i></u><br> &nbsp;&nbsp;Выход из чата<br> <u><i>/clear</i></u><br> &nbsp;&nbsp;Очистка окна чата<br> <u><i>/now "что-то"</i></u><br> &nbsp;&nbsp;Отображение в списке пользователей ваше состояние<br> <u><i>/translit [on|off]</i></u><br>
	&nbsp;&nbsp;Включение|Выключение транслита
</p>
<?php if ($out['showModCommands'] == true) {?>
<p class=normal>
	<b>Команды модераторов чата</b>:<br> <u><i>/kill "имя пользователя" "Причина" </i></u><br> &nbsp;&nbsp;Выводит пользователя из чата.<br> <u><i>/ban "имя пользователя" минут "Причина"</i></u><br> &nbsp;&nbsp;Бан пользователя на х минут. Пример : /ban "Вася Пупкин" 15 "Флуд"<br> <u><i>/unban "имя
			пользователя" "Причина"</i></u><br> &nbsp;&nbsp;Разрешить допуск к чату выбранному пользователю<br> <u><i>/allban</i></u><br> &nbsp;&nbsp;Выводит в окно чата список всех забаненых пользователей<br> <u><i>/mess "Сообщение"</i></u><br> &nbsp;&nbsp;Выводит в окно чата выделенное сообщение<br> <u><i>Модерирование
			через ID</i></u><br> &nbsp;&nbsp;Эти же команды могут быть переделаны через ID<br> &nbsp;&nbsp;Например: /killid /banid /unbanid<br> &nbsp;&nbsp;Но в этом случае пользователи чата не оповещаются
</p>
<?php }?>