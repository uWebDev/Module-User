<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button"></div>
<div>
    <h2>Дополнительная информация</h2>
</div>
<div class="button"></div>
<?php $this->stop() ?>

<!--MAIN-->
<?php $this->start('main') ?>
<?php if (isset($error['error'])) : ?>
    <?php $this->insert('error', ['error' => $error['error']]) ?>
<?php endif ?>
<form role="form" method="post" action="#" name="register_form">
    <div class="form-group<?= (isset($error['uniqueNickname']) ? ' has-error' : '') ?>">
        <label for="login"><?= $this->lng('nickname') ?></label>
        <input id="login" class="form-control" type="text" pattern="[a-zA-Z0-9]{3,20}" name="nickname" value="<?=
        (isset($formData['nickname']) ? $this->e($formData['nickname']) : '')
        ?>" placeholder="<?= $this->lng('nickname') ?>" required>
               <?php if (isset($error['uniqueNickname'])) : ?>
            <label class="label control-label"><?= $this->lng($error['uniqueNickname']) ?></label>
        <?php endif ?>
    </div>
    <button type="submit" class="btn btn-primary btn-block">
        Продолжить
    </button>
    <input type="hidden" name="form_token" value="<?= $token ?>">
</form>
<?php $this->stop() ?>