<?php
function cat_list($catId) //Вывод списка вопросов юзеру
{
  global $lil,$conf,$nfs,$em;
  
  	$repoFaqCat = $em->getRepository('Entity\EntitySFaqCat');
	$cat = $repoFaqCat->find($catId);
	$cat->setCount($cat->getCount()+1);
	$em->flush($cat);
	$count_view = $cat->getCount();
	$nav='<img src="'.$lil.'" border=0>';
	$links=$nav.'<a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a> -=- '.$nfs->unconvert_html($cat->getName());
	echo "<table align=center class=table border=0 cellspacing=0 style='width:98%'>\n";
	echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
	echo "<tr style='height:3pt;background:#121212'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td></tr>\n";
	$questions = count($cat->getItemsVal());
	foreach ($cat->getItemsVal() as $index=>$row) {
		$top_html.=$nav." <a href='#".$index."'>".$nfs->unconvert_html($row->getQuestion())."</a><br>\n";
		$bot_html.="<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'><p class=normal>".$nav." Вопрос: <a name=".$index.">".$nfs->unconvert_html($row->getQuestion())."</a></p></td></tr>\n";
		$bot_html.="<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'><p class=normal>Ответ: ".$nfs->unconvert_html($row->getAnswer())."</p><p class=normal><a href='#top'>Вернуться в начало</a></p></td></tr>\n";
		$bot_html.="<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
	}
	if ($questions == 0) {
		echo "<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'><p class=normal>Категории не существует, либо в ней нет вопросов.</p></td></tr>\n";
		echo "<tr style='height:3pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td></tr>\n";
	} else {
		echo "<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'><p>\n";
		echo $top_html;
		echo "</p></td></tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		echo $bot_html;
	}
	echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal>Просмотров категории: ".$count_view."</p></td></tr>\n";
	echo "</table>\n";
}

function forum_help() //Вывод помощи по форуму
{
global $conf,$SDK,$line,$lil,$nfs;
	$help =& $SDK->factory("help");
	$nav='<img src="'.$lil.'" border=0> ';
	if ($nfs->input['help']) {
		$helpcat = intval($nfs->input['help']);
		if ($info = $help->get_faq($helpcat)) {
			$links=$nav.'<a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a> -=- <a href="index.php?page=faq&cat=forum" title="Перейти">Помощь по форуму</a>';
			echo "<table class=table align=center border=0 cellspacing=0 style='width:98%'>\n";
			echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
	  		echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
			echo "<tr style='background:transparent'><td><p class=normal><b>".$nfs->unconvert_html($info['title'])."</b><br><img width=100% height=1 src='".$line."' vspace=3 border=0><br>".$nfs->unconvert_html($info['description'])."<br><img width=100% height=1 src='".$line."' vspace=3 border=0><br>".$nfs->unconvert_html($info['text'])."</p></td></tr>\n";
			echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
			echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal><b>".$conf[site_name]."</b></p></td></tr>\n";
			echo "</table>\n";
		} else {
			echo '<p class=normal>Раздел помощи на форуме отсутствует</p>';
		}
	} else {
		$faqs = $help->list_faqs();
		$links=$nav.'<a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a>';
		echo "<table class=table align=center border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
		echo "</table>\n";
		foreach ($faqs as $i) {
			echo "<p class=normal>".$nav." <a href='index.php?page=faq&cat=forum&help=".$i['id']."'>".$nfs->unconvert_html($i['title'])."</a><br>".$nfs->unconvert_html($i['description'])."</p>\n";
		}
	}
}

function all_cat() //Вывод категорий
{
global $conf,$lil,$nfs,$em;
	$nav='<img src="'.$lil.'" border=0> ';
	$links=$nav.'<a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a>';
	echo "<table align=center class=table border=0 cellspacing=0 style='width:98%'>\n";
	echo "<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
	echo "<tr style='height:3pt;background:#121212'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td></tr>\n";
	
	$repoFaqCat = $em->getRepository('Entity\EntitySFaqCat');
	$rows = $repoFaqCat->getAllSortedByPosition();
	$cats_count = count($rows);
	foreach ($rows as $row) {
		echo "<tr style='height:15pt'><td style='width:100%;border:solid windowtext 1.0pt'>\n";
		echo "<p class=normal>Категория: <b><a href='index.php?page=faq&cat=".$row->getId()."' title=Перейти>".$nfs->unconvert_html($row->getName())."</a></b></p></td></tr>\n";
		echo "<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'>\n";
		echo "<p class=normal>".$nfs->unconvert_html($row->getInfo())."</p></td></tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
	}
	if ($conf['faq_from_forum']==1) {
		$cats_count+=1;
		echo "<tr style='height:15pt'><td style='width:100%;border:solid windowtext 1.0pt'>\n";
		echo "<p class=normal>Категория: <b><a href='index.php?page=faq&cat=forum' title=Перейти>Помощь по использованию форума</a></b></p></td></tr>\n";
		echo "<tr style='height:15pt'><td style='border:solid windowtext 1.0pt'>\n";
		echo "<p class=normal>Вопросы и ответы по использованию функций на форуме и т.д.</p></td></tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
	}
	if ($cats_count == 0) {
		echo "<tr style='height:15pt'><td style='width:100%;border:solid windowtext 1.0pt'>\n";
		echo "<p class=normal>Категорий не существует!</p></td></tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
	}
	$repoFaqDb = $em->getRepository('Entity\EntitySFaqDb');
	$count = $repoFaqDb->getCount();
	echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'>\n";
	echo "<p class=normal>Всего в базе <b>".$count."</b> вопроса(ов) и ответа(ов).</p>\n";
	echo "</td></tr></table>\n";
}
global $nfs;
//Тело самой программы - окучивание того, куда юзер влетел и выдача результатов ему на блюдечке!
if  (isset($nfs->input['cat']) and ($nfs->input['cat'] == 'forum')){
	forum_help();
} else if (isset($nfs->input['cat']) and ($nfs->input['cat'] <> '') and ($nfs->input['cat'] <> 'forum')){
	$cat=intval($nfs->input['cat']);
	cat_list($cat);
} else {
	all_cat();
}
?>