<?php global $_MyCookie; ?>
<script type="text/javascript">MYCOOKIEJS_ACTION = '<?php echo $_MyCookie->getAction(); ?>';
    MYCOOKIEJS_MODULE = '<?php echo $_MyCookie->getModule(); ?>';
    MYCOOKIEJS_AUXILIARMODULE = '<?php echo $_MyCookie->getAuxiliarModule(); ?>';
    MYCOOKIEJS_NAMESPACE = '<?php echo str_replace('\\', '\\\\', $_MyCookie->getNamespace()); ?>';
    MYCOOKIEJS_SITE = '<?php echo $_MyCookie->getSite(); ?>';
</script>