<?php if ($_SESSION['user']['role'] == 'app-admin'): ?>
<div class="top-bar">
    <b><?= $this->Notifier->renderUpdatekanboard() ?></b>
    <?= $this->Notifier->renderUpdatePlugin() ?>
</div>
<?php endif ?>
