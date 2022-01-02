<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;

class ProductController extends AbstractController
{
    /**
     * @Route("/createproduct")
     */
    public function createAction(DocumentManager $dm)
    {
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');

        $dm->persist($product);
        $dm->flush();

        return new Response('Created product id ' . $product->getId());
    }

    /**
     * @Route("/product_show/{id}")
     */
    public function showAction(DocumentManager $dm, $id)
    {
        $product = $dm->getRepository(Product::class)->find($id);

        if (! $product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return new Response('Created zzz id ' . $product->getId());
    }

    /**
     * @Route("/productfindiden/{id}")
     */
    public function findByIndentifier(DocumentManager $dm, $id)
    {
        $repository = $dm->getRepository(Product::class);
        // query by the identifier (usually "id")
        $product = $repository->find($id);

        if (! $product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return new Response('Finded id ' . $product->getId());
    }

    /**
     * @Route("/productall")
     */
    public function findByAll(DocumentManager $dm)
    {
        $repository = $dm->getRepository(Product::class);
        // query by the identifier (usually "id")
        $products = $repository->findAll();

        if (! $products) {
            throw $this->createNotFoundException('No product found');
        }
        echo "<pre>";
        print_r($products);
        return new Response('Finded All ');
    }

    /**
     * @Route("/productfindbyone")
     */
    public function findByOne(DocumentManager $dm)
    {
        $repository = $dm->getRepository(Product::class);
        $product = $repository->findOneBy(['name' => 'Foo', 'price' => 30]);

        if (! $product) {
            throw $this->createNotFoundException('No product found');
        }
        echo "<pre>";
        print_r($product);
        return new Response('Finded By One ');
    }

    /**
     * @Route("/productupdate/{id}")
     */
    public function updateAction(DocumentManager $dm, $id)
    {
        $product = $dm->getRepository(Product::class)->find($id);

        if (! $product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $product->setName('new name');

        $dm->flush();

        return new Response('Updated');
    }

    /**
     * @Route("/productquerybuidler/{name}")
     */
    public function queryBuilder(DocumentManager $dm, $name)
    {
        $products = $dm->createQueryBuilder(Product::class)
            ->field('name')->equals($name)
            ->sort('price', 'ASC')
            ->limit(10)
            ->getQuery()
            ->execute();
            echo "<pre>";
            var_dump($products);
        return new Response('Updated');
    }

    /**
     * @Route("/productallorder")
     */
    public function findByAllOrder(DocumentManager $dm)
    {
        $products = $dm->getRepository(Product::class)
            ->findAllOrderedByName();
            echo "<pre>";
            var_dump($products);
        return new Response('Updated');
    }
}
