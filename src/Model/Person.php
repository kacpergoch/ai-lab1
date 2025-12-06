<?php
namespace App\Model;

use App\Service\Config;

class Person
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $surname = null;
    private ?string $bio = null;
    private ?string $hobbies = null;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): Person { $this->id = $id; return $this; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): Person { $this->name = $name; return $this; }

    public function getSurname(): ?string { return $this->surname; }
    public function setSurname(?string $surname): Person { $this->surname = $surname; return $this; }

    public function getBio(): ?string { return $this->bio; }
    public function setBio(?string $bio): Person { $this->bio = $bio; return $this; }

    public function getHobbies(): ?string { return $this->hobbies; }
    public function setHobbies(?string $hobbies): Person { $this->hobbies = $hobbies; return $this; }

    public static function fromArray($array): Person
    {
        $p = new self();
        $p->fill($array);
        return $p;
    }

    public function fill($array): Person
    {
        if (isset($array['id']) && ! $this->getId()) { $this->setId((int)$array['id']); }
        if (isset($array['name'])) { $this->setName($array['name']); }
        if (isset($array['surname'])) { $this->setSurname($array['surname']); }
        if (isset($array['bio'])) { $this->setBio($array['bio']); }
        if (isset($array['hobbies'])) { $this->setHobbies($array['hobbies']); }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM people';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) { $out[] = self::fromArray($r); }
        return $out;
    }

    public static function find($id): ?Person
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM people WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (! $row) { return null; }
        return self::fromArray($row);
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO people (name, surname, bio, hobbies) VALUES (:name, :surname, :bio, :hobbies)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $this->getName(),
                'surname' => $this->getSurname(),
                'bio' => $this->getBio(),
                'hobbies' => $this->getHobbies(),
            ]);
            $this->setId((int)$pdo->lastInsertId());
        } else {
            $sql = "UPDATE people SET name = :name, surname = :surname, bio = :bio, hobbies = :hobbies WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $this->getName(),
                ':surname' => $this->getSurname(),
                ':bio' => $this->getBio(),
                ':hobbies' => $this->getHobbies(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM people WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $this->getId()]);

        $this->setId(null);
        $this->setName(null);
        $this->setSurname(null);
        $this->setBio(null);
        $this->setHobbies(null);
    }
}
