<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button text-center">
    <a href="<?= $this->route('login') ?>"><span class="icon-direction-left"></span></a>
</div>
<div class="separator"></div>
<div>
    <h2><?= $this->lng('registration') ?></h2>
</div>
<div class="button"></div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<?php if ($closed) : ?>
    <div class="row">
        <div class="col-xs-1 col-sm-2"></div>
        <div class="col-xs-10 col-sm-8">
            <div class="alert alert-info text-center">
                <?= $this->lng('registration_closed') ?>
            </div>
        </div>
        <div class="col-xs-1 col-sm-2"></div>
    </div>
<?php elseif (isset($message['message'])) : ?>
    <?php $this->insert('message', ['message' => $message['message']]) ?>
<?php else: ?>
    <?php if (isset($error['error'])) : ?>
        <?php $this->insert('error', ['error' => $error['error']]) ?>
    <?php endif ?>
    <form role="form" method="post" action="#" name="register_form">
        <div class="form-group<?= (isset($error['uniqueEmail']) ? ' has-error' : '') ?>">
            <label for="email"><?= $this->lng('email') ?></label>
            <input id="email" class="form-control" type="email" name="email" placeholder="E-mail" value="<?=
            (isset($formData['email']) ? $this->e($formData['email']) : '')
            ?>"  required>

            <?php if (isset($error['uniqueEmail'])) : ?>
                <label class="label control-label"><?= $this->lng($error['uniqueEmail']) ?></label>
            <?php endif ?>
        </div>
        <div class="form-group<?= (isset($error['uniqueNickname']) ? ' has-error' : '') ?>">
            <label for="login"><?= $this->lng('nickname') ?></label>
            <input id="login" class="form-control" type="text" pattern="[a-zA-Z0-9]{3,20}" name="login" value="<?=
            (isset($formData['login']) ? $this->e($formData['login']) : '')
            ?>" placeholder="<?= $this->lng('nickname') ?>" required>

            <?php if (isset($error['uniqueNickname'])) : ?>
                <label class="label control-label"><?= $this->lng($error['uniqueNickname']) ?></label>
            <?php endif ?>
        </div>
        <div class="form-group<?= (isset($error['password']) ? ' has-error' : '') ?>">
            <label for="password"><?= $this->lng('password') ?></label>
            <input id="password" class="form-control" type="text" name="password" pattern=".{8,}" placeholder="<?= $this->lng('password') ?>" required autocomplete="off">
            <?php if (isset($error['password'])) : ?>
                <label class="label control-label"><?= $this->lng($error['password']) ?></label>
            <?php endif ?>
        </div>
        <?php $this->insert('captcha', $captcha) ?>
        <button type="submit" class="btn btn-primary btn-block">
            <?= $this->lng('registration_account') ?>
        </button>
    </form>
<?php endif ?>
<?php $this->stop() ?>