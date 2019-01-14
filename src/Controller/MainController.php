<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(CategoryRepository $CategoryRepository, ProductsRepository $ProductsRepository, Request $request)
    {
        $categories = $CategoryRepository->getAllCategoriesWithChildren();
        $products = $ProductsRepository->getLastProducts(5);
        //dump($categories);
        //dump($products);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'categories' => $categories,
            'products' => $products,
			'breadCrumbs' => null,
			'wishListCount' => count($request->getSession()->get('whishList')),
			'cartCount' => count($request->getSession()->get('cart')),
        ]);
    }
	
	
}
