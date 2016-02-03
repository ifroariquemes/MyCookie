<?php
global $_Config;
global $_MyCookie;
global $_BaseURL;
?>
<p><?= $_MyCookie->getTranslation('user', 'email.hello') ?>, <?= $data['user']->getName() ?>!</p>
<p><?= $_MyCookie->getTranslation('user', 'email.internal_1') ?>.</p>
<p><?= $_MyCookie->getTranslation('user', 'email.internal_2') ?>:</p>
<p>
    <b><?= $_MyCookie->getTranslation('user', 'label.username') ?></b>: <?= $data['user']->getEmail() ?>
    <b><?= $_MyCookie->getTranslation('user', 'label.password') ?></b>: <?= $data['password'] ?>
</p>
<p><?= $_MyCookie->getTranslation('user', 'email.internal_3') ?> <a href="<?= $_BaseURL ?>administrator/"><?= $_BaseURL ?>administrator/</a>.</p>
<p><?= $_MyCookie->getTranslation('user', 'email.internal_4') ?>.</p>
<p style="font-size: small"><?= $_MyCookie->getTranslation('user', 'email.automatic') ?>.</p>