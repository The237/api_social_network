<?php

namespace App\Request\ParamConverter;

use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PutConverter implements ParamConverterInterface
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
        if(!$request->isMethod(Request::METHOD_PUT) ){
            return;
        }  

        $object = $request->attributes->get($configuration->getName());
        
        $this->serializer->deserialize(
            $request->getContent(),
            $configuration->getClass(),
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $object]
        );
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