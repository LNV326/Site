<?php

namespace Controller;

use Controller\AbstractSiteController;

class AdverControllerDB extends AbstractSiteController {
	
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		// TODO: Auto-generated method stub
		echo <<< EOF
<p class=normal>Для того чтобы разместить рекламу своего ресурса у нас вам необходимо связаться с администратором проекта: <a href='index.php?page=contact' title='Связь с администратором'>связь</a>.</p>
<p class=normal><b>Условия размещения рекламы:</b><br>
1) Рекламируемый сайт не относится к эротике и интим услугам!<br>
2) Оплата производится через WebMoney, сразу после размещения рекламы!</p>
EOF;
	}

}