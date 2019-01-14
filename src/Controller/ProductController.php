<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;

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
    public function show(Products $product, CategoryRepository $CategoryRepository, Request $request): Response
    {
        $categories = $CategoryRepository->getAllCategoriesWithChildren();
		$category = $product->getCategory();
		$breadCrumbs = $CategoryRepository->getRecursiveParents($category);
		dump($product);

        return $this->render('product/index.html.twig', [
            'categories' => $categories,
            'product' => $product,
			'breadCrumbs' => $breadCrumbs,
			'wishListCount' => count($request->getSession()->get('whishList')),
			'cartCount' => count($request->getSession()->get('cart')),
        ]);
    }
	
	/**
	* @Route("/add-to-whish-list/{id}", name="add-to-whish-list", methods="GET")
	*/
	public function addToWhishList(Products $product, Request $request): Response
	{
	
		if ($request->hasSession() && ($session = $request->getSession())) {
			$whishList = $session->get('whishList');
			if (!is_array($whishList)) {
				$whishList = [];
			}
			$id = (integer) $request->get('id');
			if (!in_array($id, $whishList)) {
				$whishList[] = $id;
				$session->set('whishList', $whishList);
			}
		}
		
		 return $this->redirectToRoute('product_show', array('id' => $id), 301);
	}
	
	/**
	* @Route("/add-to-cart/{id}", name="add-to-cart", methods="GET")
	*/
	public function addToCart(Products $product, Request $request): Response
	{
	
		if ($request->hasSession() && ($session = $request->getSession())) {
			$cart = $session->get('cart');
			if (!is_array($cart)) {
				$cart = [];
			}
			$id = (integer) $request->get('id');
			if (!in_array($id, $cart)) {
				$cart[] = $id;
				$session->set('cart', $cart);
			}
		}
		
		 return $this->redirectToRoute('product_show', array('id' => $id), 301);
	}
}
