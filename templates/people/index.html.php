<?php
/** @var \App\Model\Person[] $people */
/** @var \App\Service\Router $router */

$title = 'People';
$bodyClass = 'index';

ob_start(); ?>
<h1>People List</h1>

<a href="<?= $router->generatePath('people-create') ?>">Create new</a>

<ul class="index-list">
    <?php foreach ($people as $p): ?>
        <li>
            <h3><?= htmlspecialchars($p->getName() . ' ' . $p->getSurname()) ?></h3>
            <?php if ($p->getBio()): ?>
                <div class="excerpt"><?= nl2br(htmlspecialchars($p->getBio())) ?></div>
            <?php endif; ?>

            <ul class="action-list">
                <li><a href="<?= $router->generatePath('people-show', ['id' => $p->getId()]) ?>">Details</a></li>
                <li><a href="<?= $router->generatePath('people-edit', ['id' => $p->getId()]) ?>">Edit</a></li>
                <li><a href="<?= $router->generatePath('people-delete', ['id' => $p->getId()]) ?>">Delete</a></li>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
