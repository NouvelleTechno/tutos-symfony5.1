<?php

namespace App\Twig;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CatsExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cats', [$this, 'getCategories'])
        ];
    }

    public function getCategories()
    {
        $cache = new FilesystemAdapter();

        $categories = $cache->get('categories_list', function(){
            return $this->em->getRepository(Categories::class)->findBy([], ['name' => 'ASC']);
        });

        return $categories;
    }
}