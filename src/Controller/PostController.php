<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/posts")
 */

class PostController extends AbstractController
{
  
    /**
     * @Route(name="api_posts_collection_get", methods={"GET"})
     * @param PostRepository $postRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(PostRepository $postRepository, SerializerInterface $serializer):JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($postRepository->findAll(),'json',["groups" =>"get"]) ,
            JsonResponse::HTTP_OK,
            [], 
            true
        );
    }
    

    /**
     * @Route("/{id}",name="api_posts_item_get", methods={"GET"})
     * @param Post $post
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(Post $post, SerializerInterface $serializer):JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($post,'json',["groups" =>"get"]) ,
            JsonResponse::HTTP_OK,
            [], 
            true
        );
    }

    /**
     * @Route(name="api_posts_collection_post", methods={"POST"})
     * @param Post $post
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $ug
     * @return JsonResponse
     */
    public function post(Post $post,EntityManagerInterface $em,
        SerializerInterface $serializer, 
        UrlGeneratorInterface $ug, ValidatorInterface $validator):JsonResponse
    {
        $post->setAuthor($em->getRepository(User::class)->findOneBy([]));
        
        $errors = $validator->validate($post);
        
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors,'json'), 
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $em->persist($post);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($post,'json',["groups"=>"get"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $ug->generate("api_posts_item_get",["id"=> $post->getId()])], 
            true
        ); 
    }

    /**
     * Undocumented function
     * @Route("/{id}",name="api_posts_item_put", methods={"PUT"})
     * @param Post $post
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function put(Post $post,EntityManagerInterface $em,ValidatorInterface $validator,SerializerInterface $serializer): JsonResponse
    {   
        $errors = $validator->validate($post);
        
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors,'json'), 
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $em->flush(); 

        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }

     /**
     * Undocumented function
     * @Route("/{id}",name="api_posts_item_delete", methods={"DELETE"})
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function delete(Post $post,EntityManagerInterface $em): JsonResponse
    {   
        $em->remove($post);
        $em->flush(); 

        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }
}
 