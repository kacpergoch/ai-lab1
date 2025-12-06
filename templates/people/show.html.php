<?php
/** @var \App\Model\Person|null $person */
/** @var \App\Service\Router $router */

$title = $person ? $person->getName() . ' ' . $person->getSurname() : 'Person';
$bodyClass = 'show';

ob_start(); ?>

<?php if (! $person): ?>
    <p>Person not found.</p>
    <p><a href="<?= $router->generatePath('people-index') ?>">Back to list</a></p>
<?php else: ?>
    <h1><?= htmlspecialchars($person->getName() . ' ' . $person->getSurname()) ?></h1>
    <?php if ($person->getBio()): ?>
        <p><?= nl2br(htmlspecialchars($person->getBio())) ?></p>
    <?php endif; ?>
    <?php if ($person->getHobbies()): ?>
        <p><strong>Hobbies:</strong> <?= nl2br(htmlspecialchars($person->getHobbies())) ?></p>
    <?php endif; ?>

    <ul class="action-list">
        <li><a href="<?= $router->generatePath('people-index') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('people-edit', ['id' => $person->getId()]) ?>">Edit</a></li>
        <li><a href="<?= $router->generatePath('people-delete', ['id' => $person->getId()]) ?>">Delete</a></li>
    </ul>
<?php endif; ?>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
?>
