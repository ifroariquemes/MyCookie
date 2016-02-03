<select <?php if ($data['readonly']) : ?>disabled="disabled"<?php endif; ?> name="accountTypeId" id="selectaccountTypeId" class="form-control" required="required">
    <option value="" data-i18n="user:label.select_acc">Select an account type...</option>
    <?php foreach ($data['accountTypes'] as $accountType) : ?>
        <option value='<?= $accountType->getId(); ?>' flag="<?= $accountType->getFlag() ?>" <?php if ($accountType->getId() == $data['accid']) : ?>selected="selected"<?php endif; ?>><?= $accountType->getName() ?></option>
    <?php endforeach; ?>
</select>