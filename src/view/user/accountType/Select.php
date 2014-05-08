<select <?php if ($data['readonly']) : ?>disabled="disabled"<?php endif; ?> name="accountTypeId" id="selectaccountTypeId" class="form-control" required="required">
    <option value=""><?php _e('Select an account type', 'user') ?>...</option>
    <?php foreach ($data['accountTypes'] as $accountType) : ?>
        <option value='<?php echo $accountType->getId(); ?>' flag="<?php echo $accountType->getFlag() ?>" <?php if ($accountType->getId() == $data['accid']) : ?>selected="selected"<?php endif; ?>><?php echo $accountType->getName() ?></option>
    <?php endforeach; ?>
</select>