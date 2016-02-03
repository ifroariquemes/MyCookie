<?php 
global $_Config;
global $_MyCookie; 
?>
<p><?= $_MyCookie->getTranslation('user', 'email.hello') ?>, <?= $data['user']->getName() ?>!</p>
<p><?= $_MyCookie->getTranslation('user', 'email.forgot_1') ?>.
    <?= $_MyCookie->getTranslation('user', 'email.forgot_2') ?>:</p>
<p><a href="<?= $data['confirmationLink'] ?>"><?= $data['confirmationLink'] ?></a></p>
<p>
    <?= $_MyCookie->getTranslation('user', 'email.forgot_3') ?> 
    <a href="mailto:<?= $_Config->mail->reply_to ?>"><?= $_Config->mail->reply_to ?></a> 
    <?= $_MyCookie->getTranslation('user', 'email.public_4') ?>.
</p>
<p style="font-size: small"><?= $_MyCookie->getTranslation('user', 'email.automatic') ?>.</p>