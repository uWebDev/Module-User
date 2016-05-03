<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div>
    <h2><?= $this->lng('output') ?></h2>
</div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<form role="form" action="#" method="post" name="logoutform">
    <p class="text-center">
        <label>
            <?= $this->lng('want_leave', [$siteTitle]) ?>
        </label>
    </p>
    <!--                <div class="checkbox">
                        <label>
                            <input type="checkbox" name="clear" value="1"><?php //$this->lng('remove_authorization')            ?>
                        </label>
                    </div>-->
    <button type="sybmit" name="logout" class="btn btn-default btn-block">
        <?= $this->lng('y') ?>
    </button>
    <a href="<?= $this->route('user') ?>" class="btn btn-danger btn-block" role="button">
        <?= $this->lng('n') ?>
    </a>
    <input type="hidden" name="form_token" value="<?= $token ?>">
</form>
<?php $this->stop() ?>