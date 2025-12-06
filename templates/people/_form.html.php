<?php
/** @var \App\Model\Person|null $person */
?>

<?php
$name = $person?->getName() ?? '';
$surname = $person?->getSurname() ?? '';
$bio = $person?->getBio() ?? '';
$hobbies = $person?->getHobbies() ?? '';
?>

<div class="form-group">
    <label for="name">Name</label>
    <input id="name" type="text" name="person[name]" required value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
</div>

<div class="form-group">
    <label for="surname">Surname</label>
    <input id="surname" type="text" name="person[surname]" value="<?= htmlspecialchars($surname, ENT_QUOTES, 'UTF-8') ?>">
</div>

<div class="form-group">
    <label for="bio">Bio</label>
    <textarea id="bio" name="person[bio]"><?= htmlspecialchars($bio, ENT_QUOTES, 'UTF-8') ?></textarea>
</div>

<div class="form-group">
    <label for="hobbies">Hobbies</label>
    <textarea id="hobbies" name="person[hobbies]"><?= htmlspecialchars($hobbies, ENT_QUOTES, 'UTF-8') ?></textarea>
</div>

<div class="form-group">
    <input type="submit" value="Submit">
</div>
