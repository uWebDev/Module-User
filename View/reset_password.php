<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button text-center">
    <a href="<?= $this->route('login') ?>"><span class="icon-direction-left"></span></a>
</div>
<div class="separator"></div>
<div>
    <h2><?= $this->lng('request_password_reset') ?></h2>
</div>
<div class="button"></div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<?php if (isset($message['message'])) : ?>
    <?php $this->insert('message', ['message' => $message['message']]) ?>
<?php else: ?>
    <?php if (isset($error['error'])) : ?>
        <?php $this->insert('error', ['error' => $error['error']]) ?>
    <?php endif ?>
    <form role="form" method="post" action="#" name="reset_form">
        <div class="form-group<?= (isset($error['email']) ? ' has-error' : '') ?>">
            <label for="email"><?= $this->lng('enter_email_user') ?></label>
            <input id="email" class="form-control" type="email" name="email" placeholder="E-mail" required>
            <?php if (isset($error['email'])) : ?>
                <p><label class="label control-label"><?= $this->lng($error['email']) ?></label></p>
            <?php endif ?>
        </div>
        <?php $this->insert('captcha', $captcha) ?>
        <button type="submit" class="btn btn-primary btn-block">
            <?= $this->lng('reset_password') ?>
        </button>
    </form>
<?php endif ?>
<?php $this->stop() ?>