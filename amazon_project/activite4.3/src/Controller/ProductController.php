<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Category;
use  App\Repository\CategoryRepository;
use  App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

     /**
     * @Route("/createproduct", name="product")
     */
    public function create(ManagerRegistry $doctrine, CategoryRepository $categoryRepository): Response
    {
    
        $category=$categoryRepository->findCategoryById(1);
        $product = new Product();
        $product->setLabel('robe');
        $product->setPrice(200);
        $product->setQuantity(5);
        // relates this product to the category
        $product->setCategory($category);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response(
            'Saved new product with id: '.$product->getId()
            .' and new category with id: '.$category->getId()
        );
    }
/**
     * @Route("/get-all-products", name="get-all-products")
     */
    public function getproduct(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Product::class);
        $products=$repo->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'productController',
            'products' =>$products
        ]);
      
    }
/**
     * @Route("/productsbycategory/{category_id}", name="get-category-products")
     */
    public function showProducts(ManagerRegistry $doctrine, ProductRepository $produitRepository, int $category_id): Response
    {
        $produits = $produitRepository->findBy(['category' => $category_id]);
        return $this->render('product/index.html.twig', [
            'controller_name' => 'productController',
            'products' =>$produits
        ]);
       
    }

}
