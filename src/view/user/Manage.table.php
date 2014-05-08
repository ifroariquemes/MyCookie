<?php global $_MyCookieUser; ?>
<?php if (!empty($data)) : ?>
    <table class="table table-striped">
        <thead>
            <tr>      
                <th><?php _e('Name', 'user') ?></th>
                <th><?php _e('Username', 'user') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $user): ?>
                <tr>          
                    <td>
                        <a href="#"><?php echo sprintf('%s %s', $user->getName(), $user->getLastName()); ?></a>
                        <?php if (!$user->getStatus()) : ?><span class="text-error"><?php echo sprintf('(%s)', $user->getStatusStr()); ?></span><?php endif; ?>
                    </td>
                    <td><?php echo $user->getLogin(); ?></td>
                    <td class="hidden-sm hidden-xs"><button class="btn pull-right" onclick="_Biscoito.IrPara('administrador/usuario/editar/<?php echo $user->getId() ?>')"><i class="fa fa-pencil"></i> <?php _e('Edit', 'user') ?></button></td>
                </tr>
            <?php endforeach; ?>            
        </tbody>
    </table>
<?php else : ?>
    <?php _e('There is no user with this account type', 'user') ?>
<?php endif; ?>