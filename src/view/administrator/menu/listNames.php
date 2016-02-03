<?php global $_MyCookie; ?>
<ul class="dropdown-menu">
    <?php foreach ($data as $menu): ?>
        <li>
            <a href="<?= $_MyCookie->mountLink('administrator', $menu->getDirectory()); ?>">
                <?= $menu->getName(); ?>
            </a>
        </li>
    <?php endforeach; ?>    
    <li class="divider"></li>
    <li><a href="<?= $_MyCookie->mountLink('administrator') ?>"><i class="fa fa-home"></i> <span data-i18n="admin:button.home">Home</span></a></li>
</ul>