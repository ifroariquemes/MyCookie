<ul class="dropdown-menu">
    <?php foreach ($menuList as $menu): ?>
        <li>
            <a href="<?php echo $GLOBALS['_Biscoito']->montarLink('administrador', $menu->getDirectory()); ?>">
                <?php echo $menu->getName(); ?>
            </a>
        </li>
    <?php endforeach; ?>    
    <li class="divider"></li>
    <li><a href="#" onclick="_Biscoito.IrPara('administrador')"><i class="icon-home"></i> Início</a></li>
</ul>