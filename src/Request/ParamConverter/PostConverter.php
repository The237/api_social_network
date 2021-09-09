<?php

namespace App\Request\ParamConverter;
use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;

class PostConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if(!$request->isMethod(Request::METHOD_POST)){
            return;
        }  
        $object = $this->serializer->deserialize($request->getContent(),$configuration->getClass(),'json');

        $request->attributes->set($configuration->getName(),$object);
    }

    /**
     * @param ParamConverter $configuration
     * @return void
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Post::class; 
    }
}