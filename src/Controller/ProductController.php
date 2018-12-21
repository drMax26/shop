<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Products $product, CategoryRepository $CategoryRepository): Response
    {
        $categories = $CategoryRepository->getAllParent();
        $countParent = count($categories);

        for ($i = 0; $i < $countParent; $i++) {
            $categories[$i]->setChildren($CategoryRepository->getChildrenByParentId($categories[$i]->getId()));
        }

        //$products = $ProductsRepository->getProductsByCategory($category->getId());
        dump($categories);
        dump($product);

        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'product' => $product,
        ]);
        //return $this->render('category/show.html.twig', ['category' => $category]);
    }
}
