<?php 

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';
require_once '../src/Template/bootstrap.php';

global $templating;

echo $templating->render('test.html.php', [
    'firstname' => 'Fabien'
]);
echo $templating->render('test.html.tpl', [
    'firstname' => 'Nikolas'
]);

echo 'Проверка на отсутствие шаблона: ';
try {
    $templating->render('notExisted.html.php');
} catch (InvalidArgumentException $e) {
    echo 'успешно<br/>';
}

?>