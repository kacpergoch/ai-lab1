<?php
namespace App\Controller;

use App\Model\Person;
use App\Service\Router;

class PersonController
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function indexAction(): void
    {
        $people = Person::findAll();
        $router = $this->router;
        require __DIR__ . '/../../templates/people/index.html.php';
    }

    public function showAction(): void
    {
        $id = $_GET['id'] ?? null;
        $person = $id ? Person::find($id) : null;
        $router = $this->router;
        require __DIR__ . '/../../templates/people/show.html.php';
    }

    public function createAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['person'] ?? $_POST;
            $p = new Person();
            $p->setName($data['name'] ?? null);
            $p->setSurname($data['surname'] ?? null);
            $p->setBio($data['bio'] ?? null);
            $p->setHobbies($data['hobbies'] ?? null);
            $p->save();

            $this->router->redirect($this->router->generatePath('people-index'));
            exit;
        }

        $person = new Person();
        $router = $this->router;
        require __DIR__ . '/../../templates/people/create.html.php';
    }

    public function editAction(): void
    {
        $id = $_GET['id'] ?? null;
        $person = $id ? Person::find($id) : null;
        if (! $person) {
            $this->router->redirect($this->router->generatePath('people-index'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['person'] ?? $_POST;
            $person->setName($data['name'] ?? null);
            $person->setSurname($data['surname'] ?? null);
            $person->setBio($data['bio'] ?? null);
            $person->setHobbies($data['hobbies'] ?? null);
            $person->save();

            $this->router->redirect($this->router->generatePath('people-show', ['id' => $person->getId()]));
            exit;
        }

        $router = $this->router;
        require __DIR__ . '/../../templates/people/edit.html.php';
    }

    public function deleteAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $person = Person::find($id);
                if ($person) { $person->delete(); }
            }
            $this->router->redirect($this->router->generatePath('people-index'));
            exit;
        }

        $id = $_GET['id'] ?? null;
        $person = $id ? Person::find($id) : null;
        $router = $this->router;
        require __DIR__ . '/../../templates/people/delete.html.php';
    }
}
