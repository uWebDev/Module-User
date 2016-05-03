<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button text-center">
    <a href="<?= $this->route('home') ?>"><span class="icon-direction-left"></span></a>
</div>
<div class="separator"></div>
<div>
    <h2>Личный кабинет</h2>
</div>
<div class="separator"></div>
<div class="button text-center">
    <a href="<?= $this->route('logout') ?>"><span class="icon-logout"></span></a>
</div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<div class="text-center">
    <?= $this->e($username) ?>, личный кабинет в разработке.
</div>
<?php if ($isAdmin) : ?>
    <a href="<?= $this->route('admin') ?>">
        Админ кабинет
    </a>
    <button class="btn btn-danger btn-block">Кабинет</button>
<?php endif ?>
<?php $this->stop() ?>