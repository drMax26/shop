<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(CategoryRepository $CategoryRepository, ProductsRepository $ProductsRepository)
    {
        $categories = $CategoryRepository->getAllParent();
        $countParent = count($categories);

        for ($i = 0; $i < $countParent; $i++) {
            $categories[$i]->setChildren($CategoryRepository->getChildrenByParentId($categories[$i]->getId()));
        }

        $products = $ProductsRepository->getLastProducts(5);
        dump($categories);
        dump($products);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
