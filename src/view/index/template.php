<?php
/* @var $_MyCookie \lib\MyCookie */
global $_MyCookie;
$_MyCookie->CSSBundle();
$_MyCookie->RequireJS();
?>
<h1>Tmpl Padrao Index</h1>
<?php echo $view; ?>
<h4>Fechou!</h4>
<?php $_MyCookie->JSBundle() ?>