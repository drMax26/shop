<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllParent()
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.parent is null')
            ->getQuery()
            ->getResult();
    }
	
	public function getCategoryById($id)
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.id = :id')
			->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getChildrenByParentId($parentId)
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.parent = :parentId')
            ->setParameter('parentId', $parentId)
            ->getQuery()
            ->getResult();
    }

    public function getByParentChildrenId($categoryId)
    {
        return $this->createQueryBuilder('category')
            ->andWhere('category.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }
	
	public function getAllCategoriesWithChildren()
	{
		$categories = $this->getAllParent();
		$countParent = count($categories);

        for ($i = 0; $i < $countParent; $i++) {
			$categories[$i]->setChildren($this->getRecursiveChild($categories[$i]->getId()));
        }
		return $categories;
	}
	
	public function getRecursiveChild($id)
	{
		$categories = $this->getChildrenByParentId($id);
        $countParent = count($categories);
		
        for ($i = 0; $i < $countParent; $i++) {
            $categories[$i]->setChildren($this->getRecursiveChild($categories[$i]->getId()));
		}
		return $categories;
	}
	
	public function getRecursiveParents($category)
	{
		$categories = $category;
		//dump($categories);
		
		if ($ParentId = $category->getParent()) {
			$categories->setFullParent($this->getRecursiveParents($this->getByParentChildrenId($ParentId)[0]));
		}
		/**/
		return $categories;
	}

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
