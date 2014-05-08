<?php global $_MyCookie; ?>
<div class="row">
    <div class="col-lg-12">
        <?php foreach (controller\user\accountType\AccountTypeControl::ListAccountTypes() as $accountType) : ?>
            <h3><?php echo $accountType->getName(); ?></h3>
            <?php controller\user\UserController::ShowUserTableByType($accountType->getId()); ?>
        <?php endforeach; ?>

        <div class="clear"></div>
    </div>
</div>
<nav id="admin-navbar" class="navbar navbar-default navbar-fixed-bottom" role="navigation">    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="align-center">
        <a href="<?php echo $_MyCookie->mountLink('administrator', 'user', 'add') ?>" class="navbar-link">
            <i class="fa fa-plus-circle fa-4x"></i>
        </a>
    </div><!-- /.navbar-collapse -->
</nav>