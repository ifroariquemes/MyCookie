require(['jquery', 'i18next'], function ($) {
    $(function () {
        var lngComp = navigator.language.split("-");
        var lngUser = (lngComp[0]);
        console.log("User language: %s", lngUser);
        var option = {
            <?php if ($data['lang'] != 'user') : ?>lng: "<?= $data['lang'] ?>",
            <?php else : ?>lng: lngUser,
            <?php endif; ?>
            resGetPath: '<?= $data['site'] ?>src/lang/__lng__/__ns__.json',
            ns: {
                namespaces: [<?= $data['ns'] ?>]
            },
            useLocalStorage: true,
            localStorageExpirationTime: 86400000 // in ms
        };
        i18n.init(option, function (t) {                
            $("html").i18n();
        });
    });
});      