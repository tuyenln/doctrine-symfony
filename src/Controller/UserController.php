<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;

class UserController extends AbstractController
{
    /**
     * @Route("/user")
     */
    public function createAction(DocumentManager $dm)
    {
        $user = new User();
        $user->setName('nguyen van a');
        $user->setType('admin');
        $user->setAge(30);

        // $user->setName('le ngoc tuyen');
        // $user->setType('super admin');
        // $user->setAge(35);

        $dm->persist($user);
        $dm->flush();

        return new Response('Created user id ' . $user->getName());
    }

    /**
     * @Route("/usersingle")
     */
    public function getSingleResult(DocumentManager $dm)
    {
        $user = $dm->createQueryBuilder(User::class)
                    ->field('name')->equals('le ngoc tuyen')
                    ->getQuery()
                    ->getSingleResult();
        var_dump($user->getName());
        return new Response('Finded userName ' . $user->getName());
    }
}
