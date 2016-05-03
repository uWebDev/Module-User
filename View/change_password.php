<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button text-center">
    <a href="<?= $this->route('login') ?>"><span class="icon-direction-left"></span></a>
</div>
<div class="separator"></div>
<div>
    <h2><?= $this->lng('reset_password') ?></h2>
</div>
<div class="button"></div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<?php if (isset($error['error'])) : ?>
    <?php $this->insert('error', ['error' => $error['error']]) ?>
<?php endif ?>
<form role="form" method="post" action="#" name="password_form">
    <div class="form-group<?= (isset($error['password']) ? ' has-error' : '') ?>">
        <label for="password">Новый пароль:</label>
        <input id="password" class="form-control" type="password" name="password" placeholder="Новый пароль" pattern=".{8,}" required autocomplete="off">
        <?php if (isset($error['password'])) : ?>
            <label class="label control-label"><?= $this->lng($error['password']) ?></label>
        <?php endif ?>
    </div>
    <div class="form-group<?= (isset($error['password_repeat']) ? ' has-error' : '') ?>">
        <input id="password_repeat" class="form-control" type="password" name="password_repeat" placeholder="Новый пароль еще раз" pattern=".{8,}" required autocomplete="off">
        <?php if (isset($error['password_repeat'])) : ?>
            <label class="label control-label"><?= $this->lng($error['password_repeat']) ?></label>
        <?php endif ?>
    </div>
    <button type="submit" class="btn btn-lg btn-primary btn-group-justified">
        Отправить новый пароль
    </button>
</form>
<?php $this->stop() ?>