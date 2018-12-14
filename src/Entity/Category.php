<?php

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class Category
{
    use ORMBehaviors\Translatable\Translatable; 

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $someFieldYouDoNotNeedToTranslate;
}