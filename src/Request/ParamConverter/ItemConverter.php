<?php

namespace App\Request\ParamConverter;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ItemConverter implements ParamConverterInterface
{
    /**
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param ParamConverter $configuration
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {    
        if(!$request->attributes->has("id") )
        {
            return;
        }  

        $object = $this->em
            ->getRepository($configuration->getClass()) 
            ->find($request->attributes->get("id"))
        ;
        $request->attributes->set($configuration->getName(),$object);
    }

    /**
     * Undocumented function
     *
     * @param ParamConverter $configuration
     * @return void
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Post::class; 
    }
}