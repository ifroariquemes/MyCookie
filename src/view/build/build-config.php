require([
    <?php foreach ($data as $key => $value) : ?>
        '<?= $key ?>',
    <?php endforeach; ?>
], function() { });