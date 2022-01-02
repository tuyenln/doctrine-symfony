<?php

namespace App\Controller;

use Doctrine\DBAL\Driver\IBMDB2\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }

    /***
     * @Route("/users/create", name="users_create")
     */
    public function create(Connection $connection, Request $request)
    {
        $connection->insert('user', ['username' => $request->request->get('username')]);
        return $this->json([
            'success' => true,
            'message' => sprintf('Created %s successfully !' , $request->request->get('username'))
        ]);
    }
}
