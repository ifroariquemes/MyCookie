<?php global $_MyCookie; ?>
<ul class="dropdown-menu">
    <?php foreach ($data as $menu): ?>
        <li>
            <a href="<?php echo $_MyCookie->mountLink('administrator', $menu->getDirectory()); ?>">
                <?php echo $menu->getName(); ?>
            </a>
        </li>
    <?php endforeach; ?>    
    <li class="divider"></li>
    <li><a href="<?php echo $_MyCookie->mountLink('administrator') ?>"><i class="fa fa-home"></i> <?php _e('Home', 'menu') ?></a></li>
</ul>