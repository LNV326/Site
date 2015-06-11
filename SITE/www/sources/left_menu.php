<?php
function menu_row($info,$type,$url,$open_new,$new_img) {
global $lil,$new,$nfs;
	if ($type == 'link') {
		$row_link=$url;
	} else {
		$row_link="/index.php?page=".$nfs->unconvert_html($url);
	}	
	if ($open_new == '1') {
		$row_target=" target='_blank'";
	} else {
		$row_target="";
	}
	if ($new_img == '1') {
		return "<div class=\"mr_n\"><div class=\"mrr_n\"><span></span></div>".($type=='link' ? "<noindex>" : "")."<a href='".$nfs->unconvert_html($row_link)."'".$row_target.($type=='link' ? " rel=\"nofollow\"" : "").">".$nfs->unconvert_html($info)."</a>".($type=='link' ? "</noindex>" : "")."</div>";
	} else {
		return "<div class=\"mr_n\">".($type=='link' ? "<noindex>" : "")."<a href='".$nfs->unconvert_html($row_link)."'".$row_target.($type=='link' ? " rel=\"nofollow\"" : "").">".($info=='Купить Most Wanted' ? '<b>' : '').$nfs->unconvert_html($info).($info=='Купить Most Wanted' ? '</b>' : '')."</a>".($type=='link' ? "</noindex>" : "")."</div>";
	}
}

function menu_title_change($name,$type,$id) {
global $nfs;
	return "<div style='clear:both;'></div><div class=\"mtl_n mtl_n_p\" onclick='menuLoad(this, \"m".$id."\")'><i".($type=='-' ? " class=\"expanded\"" : "")."></i><b>".$nfs->unconvert_html($name)."</b></div><div style='clear:both;'></div>";
}

function leftmenu_go() {
global $nfs,$DB,$sdk_info, $em;
	$i=0;
	$noshow=null;
	
	if (!empty($sdk_info[id]) AND ($sdk_info[id]<>'0')) {
		if (!empty($sdk_info[menu_viever])) {
			foreach( explode( ",", $sdk_info[menu_viever]) as $el ) {
				$noshow[$i]=$el;
				$i+=1;
			}
		}
	} else {
		if (!$_COOKIE['menu_viever']==null) {
			foreach( explode( ",", $_COOKIE['menu_viever']) as $el ) {
				$noshow[$i]=$el;
				$i+=1;
			}	
		}
	}

	$return_html="";
	$id=0;
	$items_count=1;
	$repo = $em->getRepository('Entity\EntitySMenuCat');
	$cats = $repo->getAllCategories();
// 	$DB->query("SELECT c.id,c.name,i.info,i.type,i.url,i.new,i.open_new FROM s_menu_cat c LEFT JOIN s_menu_items i ON (c.id=i.cat_id) ORDER BY c.poz, i.poz ASC");
	foreach ($cats as $row) {
// 	while ($row = $DB->fetch_row()) {		
		if (in_array($row->getId(),$noshow)) {
			if ($id <> $row->getId()) {
				if ($id<>0) {
					$return_html.="</div>\n";
				}
				$return_html.=menu_title_change($row->getName(),"+",$row->getId());
				$id=$row->getId();
				$items_count=0;
			}
			if ($items_count==0) {
				$return_html.="<div id=\"m".$row->getId()."\">";
				$items_count+=1;
			}
		} else {
			if ($id<>$row->getId()) {
				if ($id<>0) {
					$return_html.="</div>\n";
				}
				$return_html.=menu_title_change($row->getName(),"-",$row->getId());
				$return_html.="<div id=\"m".$row->getId()."\">";
				$id=$row->getId();
				$items_count=0;
			}
			$items = $row->getItemsVal();			
			foreach ($items as $item) {
				$return_html.=menu_row($item->getInfo(),$item->getType(),$item->getUrl(),$item->getOpenNew(),$item->getNew());
				$items_count+=1;
			}
		}
	}
	$return_html.="</div>\n";
	$return_html=str_replace("&","&amp;",$return_html);
	return $return_html;
}
?>