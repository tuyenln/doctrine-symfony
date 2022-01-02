<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Document\Product as ProductDocument;
use Doctrine\ODM\MongoDB\DocumentManager;

class ProductController extends AbstractController
{

    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Laptop');
        $product->setPrice(50000);
        $product->setDescription('Macbook');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/product1/{id}", name="product_show1")
     */
    public function show1(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository
            ->find($id);
        return new Response('Check out this great product: '.$product->getName());
    }

    /**
     * @Route("/productfind/{id}", name="product_find")
     */
    public function findSingle(ManagerRegistry $doctrine, int $id): Response
    {

        // look for a single Product by its primary key (usually "id")
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        return new Response('Check out ok: '.$product->getName());

    }

    /**
     * @Route("/productfindname/", name="product_name")
     */
    public function fndOneByName (ManagerRegistry $doctrine ) : Response
    {
        $repository = $doctrine->getRepository(Product::class);
        // look for a single Product by name
        $product = $repository->findOneBy(['name' => 'Keyboard']);
        // or find by name and price
        $product = $repository->findOneBy([
            'name' => 'Keyboard',
            'price' => 1999,
        ]);
        return new Response('Check out ok: '. 'id: ' . $product->getId() .'-----'. $product->getPrice());
    }

    /**
     * @Route("/productfindby/", name="product_by")
     */
    public function findBy(ManagerRegistry $doctrine ) : Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findBy(
            ['name' => 'Keyboard'],
            ['price' => 'ASC']
        );
        echo "<pre>";
        var_dump($products);die;
        return new Response('Check out ok: '. 'id: ');
    }

    /**
     * @Route("/productfindall/", name="product_all")
     */
    public function findAll(ManagerRegistry $doctrine ) : Response
    {
        $repository = $doctrine->getRepository(Product::class);
        // look for *all* Product objects
        $products = $repository->findAll();
        echo "<pre>";
        var_dump($products);die;
        return new Response('Check out ok: '. 'id: ' . $products);
    }

    /**
     * @Route("/product/edit/{id}")
     */
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
    }

    /**
     * @Route("/product/delete/{id}")
     */
    public function delete (ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Check out ok: '. 'id: ' . $id);
    }

    /**
     * @Route("/createproduct")
     */
    public function createAction(DocumentManager $dm)
    {
        $product = new ProductDocument();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');

        $dm->persist($product);
        $dm->flush();

        return new Response('Created product id ' . $product->getId());
    }
}
