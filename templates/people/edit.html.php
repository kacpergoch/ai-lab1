<?php
/** @var \App\Model\Person|null $person */
/** @var \App\Service\Router $router */

$title = $person ? "Edit Person {$person->getName()} {$person->getSurname()}" : 'Edit Person';
$bodyClass = 'edit';

ob_start(); ?>

<?php if (! $person): ?>
    <p>Person not found.</p>
    <p><a href="<?= $router->generatePath('people-index') ?>">Back to list</a></p>
<?php else: ?>
    <h1><?= htmlspecialchars($title) ?></h1>

    <form action="<?= $router->generatePath('people-edit', ['id' => $person->getId()]) ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="people-edit">
        <input type="hidden" name="id" value="<?= $person->getId() ?>">
    </form>

    <ul class="action-list">
        <li><a href="<?= $router->generatePath('people-index') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('people-delete') ?>" method="post" style="display:inline">
                <input type="hidden" name="id" value="<?= $person->getId() ?>">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
            </form>
        </li>
    </ul>
<?php endif; ?>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
