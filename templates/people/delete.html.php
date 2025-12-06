<?php
/** @var \App\Model\Person|null $person */
/** @var \App\Service\Router $router */

$title = 'Delete Person';
$bodyClass = 'delete';

ob_start(); ?>

<?php if (! $person): ?>
    <p>Person not found.</p>
    <p><a href="<?= $router->generatePath('people-index') ?>">Back to list</a></p>
<?php else: ?>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p>Do you want to delete person: <strong><?= htmlspecialchars($person->getName() . ' ' . $person->getSurname()) ?></strong>?</p>

    <form action="<?= $router->generatePath('people-delete') ?>" method="post">
        <input type="hidden" name="id" value="<?= $person->getId() ?>">
        <input type="submit" value="Yes">
        <a href="<?= $router->generatePath('people-show', ['id' => $person->getId()]) ?>">Cancel</a>
    </form>
<?php endif; ?>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
