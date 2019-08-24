<?php
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\DelegatingEngine;
use Template\PhpEngineExtended;
use Template\SmartyEngine;

// Define template paths
define('VIEW_DIR', __DIR__ . '/1/');
define('WWW_DIR', __DIR__ . '../www/');

// Init PHP template engine
$phpTemplating = new PhpEngineExtended(new TemplateNameParser(), new FilesystemLoader(VIEW_DIR . '%name%'));

// Add global template params
$phpTemplating->addGlobal('conf', $conf);
$phpTemplating->addGlobal('nfs', $nfs);
// $phpTemplating->addGlobal('style_id', '1');
// $phpTemplating->addGlobal('SDK', $SDK);
// $phpTemplating->addGlobal('lang', $lang);

// Init Smarty template engine
$smartyTemplating = new SmartyEngine();
$smartyTemplating->setTemplateDir(VIEW_DIR);
$smartyTemplating->setCompileDir(WWW_DIR . '/temp/tpl');
$smartyTemplating->setCacheDir(WWW_DIR . '/temp/cache');

// $this->config_dir = SMARTY_DIR.'configs';
// //preset
// $this->use_sub_dirs = true;
// $this->compile_check = true; //каждый раз проверяет, изменился или нет текущий шаблон с момента последней компиляции. default=true;
// $this->force_compile = false; //(пере)компилировать шаблоны при каждом вызове. Перекрывает действие $compile_check. default=false;
// $this->caching = 2; //Кеширование (для каждого шаблона свой life_time)

// 
$templating = new DelegatingEngine([
    $phpTemplating,     // for files like 'some.html.php'
    $smartyTemplating   // for files like 'some.html.tpl'
]);

?>