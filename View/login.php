<?php $this->layout('page') ?>

    <!--CSS-->

<?php $this->start('css') ?>
    <style>
        .soc {
            display: block;
            /*border: 1px solid black;*/
            overflow: hidden;
            margin: 0 auto;
            width: 306px;
        }

        .sbuttons {
            overflow: hidden;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        .sbuttons a {

            width: 43px;
            height: 43px;
            display: block;
            float: left;
            margin-right: 4px;
            margin-left: 4px;
            background-color: #aebac8;
            background-image: url(https://id.tmtm.ru/img/1427184232/sprite.all.png);
            background-repeat: no-repeat;
            -webkit-border-radius: 43px;
            -moz-border-radius: 43px;
            border-radius: 43px;

            text-decoration: none;
            color: #409fe9;
        }

        .sbuttons a:hover {
            background-color: #568dd8;
        }

        .sbuttons a.facebook {
            background-position: 0px -75px;
        }

        .sbuttons a.vkontakte {
            background-position: -55px -75px;
        }

        .sbuttons a.twitter {
            background-position: -110px -75px;
        }

        .sbuttons a.github {
            background-position: -165px -75px;
        }

        .sbuttons a.liveid {
            background-position: -122px -171px;
        }

        .sbuttons a.google {
            background-position: -218px -75px;
        }
    </style>
<?php $this->stop() ?>

    <!--HEADER-->

<?php $this->start('header') ?>
    <div class="button text-center">
        <a href="<?= $this->route('home') ?>"><span class="icon-direction-left"></span></a>
    </div>
    <div class="separator"></div>
    <div>
        <h2><?= $this->lng('authorization') ?></h2>
    </div>
    <div class="button"></div>
<?php $this->stop() ?>

    <!--MAIN-->

<?php $this->start('main') ?>
<?php if (isset($message['message'])) : ?>
    <?php $this->insert('message', ['message' => $message['message']]) ?>
<?php elseif (isset($error['error'])) : ?>
    <?php $this->insert('error', ['error' => $error['error']]) ?>
<?php endif ?>
    <div class="soc">
        <div class="sbuttons">
            <a href="<?= $this->route('oauth', ['site' => 'yandex']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="github" title="Войти с помощью Yandex"></a>
            <a href="<?= $this->route('oauth', ['site' => 'mailru']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="twitter" title="Войти с помощью Mail.ru"></a>
            <a href="<?= $this->route('oauth', ['site' => 'vk']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="vkontakte" title="Войти с помощью Вконтакте"></a>
            <a href="<?= $this->route('oauth', ['site' => 'okru']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="google" title="Войти с помощью Одноклассники"></a>
            <a href="<?= $this->route('oauth', ['site' => 'facebook']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="facebook" title="Войти с помощью Facebook"></a>
            <a href="<?= $this->route('oauth', ['site' => 'google']) ?>" data-remote="true" data-method="post"
               data-post-consumer="default" data-post-state="" class="google" title="Войти с помощью Одноклассники"></a>
        </div>
    </div>
    <form role="form" method="post" action="#" name="loginform">
        <div class="form-group<?= (isset($error['nickname']) ? ' has-error' : '') ?>">
            <label for="login"><?= $this->lng('login') ?></label>
            <input id="login" type="text" name="login" class="form-control"
                   placeholder="<?= $this->lng('nickname') ?>/E-mail" required>
            <?php if (isset($error['nickname'])) : ?>
                <label class="label control-label"><?= $this->lng($error['nickname']) ?></label>
            <?php endif ?>
        </div>
        <div class="form-group<?= (isset($error['password']) ? ' has-error' : '') ?>">
            <label for="password"><?= $this->lng('password') ?></label>
            <input type="password" name="password" class="form-control" id="password" pattern=".{8,}"
                   placeholder="<?= $this->lng('password') ?>" required autocomplete="off">
            <?php if (isset($error['password'])) : ?>
                <label class="label control-label"><?= $this->lng($error['password']) ?></label>
            <?php endif ?>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="rememberme" value="1" checked="checked"><?= $this->lng('remember') ?>
            </label>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><?= $this->lng('sign_in') ?></button>
    </form>
    <nav class="nav-list">
        <ul class="nav nav-pills nav-justified">
            <li>
                <a href="<?= $this->route('resetPassword') ?>" class="btn btn-default"><?= $this->lng('forgot_password')
                    ?></a>
            </li>
            <?php if (!$closed) : ?>
                <li>
                    <a href="<?= $this->route('register') ?>"
                       class="btn btn-default"><?= $this->lng('registration_account') ?></a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
<?php $this->stop() ?>