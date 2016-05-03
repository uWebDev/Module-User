<?php $this->layout('page') ?>

<!--HEADER-->

<?php $this->start('header') ?>
<div class="button text-center">
    <a href="<?= $this->route('home') ?>"><span class="icon-direction-left"></span></a>
</div>
<div class="separator"></div>
<div>
    <h2>Онлайн</h2>
</div>
<div class="separator"></div>
<div class="button text-center">
    <?php if ($isGuest) : ?>
        <a href="<?= $this->route('login') ?>">
            <i class="icon-login"></i>
        </a>
    <?php else : ?>
        <a href="<?= $this->route('user') ?>">
            <i class="icon-menu"></i>
        </a>
    <?php endif ?>
</div>
<?php $this->stop() ?>

<!--MAIN-->

<?php $this->start('main') ?>
<ul class="list-group media-list">
    <?php if (!empty($list)) : ?>
        <?php foreach ($list as $value): ?>
            <li class="list-group-item media" style="margin: -1px;">
                <a class="pull-left" href="#">
                    <img class="media-object" src="/default/img/logo.png" alt="..." width="48">
                </a>
                <div class="media-body small">
                    <div><strong>User Agent:</strong> <?= $this->e($value['userAgent']) ?></div>
                    <div><strong>Ip:</strong> <?= $this->e($value['ip'], 'long2ip') ?></div>
                    <div><small><?= date("Y-m-d H:i:s", $value['timestamp']) ?></small></div>
                </div>
            </li>
        <?php endforeach ?>
    <?php else : ?>
        <li class="list-group-item media">
            Пусто
        </li>
    <?php endif ?>
</ul>
<?php $this->stop() ?>