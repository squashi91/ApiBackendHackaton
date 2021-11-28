<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/new', name: 'new_product')]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        SerializerInterface $serializer): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($product);
                $entityManager->flush();
                $response->setData([
                    'success' => true,
                    'data' => $serializer->serialize($product, 'json'),
                ]);
                return $response;
            }
            $response->setData([
                'success' => true,
                'data' => $serializer->serialize($form, 'json'),
            ]);
        }else{
            $response->setData([
                'success' => false,
                'error' => 'The request must be of type json',
                'data' => null,
            ]);
        }
        return $response;
    }

    #[Route('/read/{id}', name: 'read_product')]
    public function read(Request $request, Product $product, SerializerInterface $serializer): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $response->setData([
                'success' => true,
                'data' => $serializer->serialize($product, 'json')
            ]);
        }else{
            $response->setData([
                'success' => false,
                'error' => 'The request must be of type json',
                'data' => null,
            ]);
        }
        return $response;
    }

    #[Route('/update/{id}', name: 'update_product')]
    public function update(Request $request,
                           Product $product,
                           SerializerInterface $serializer,
                           EntityManagerInterface $entityManager): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($product);
                $entityManager->flush();
                $response->setData([
                    'success' => true,
                    'data' => $serializer->serialize($product, 'json'),
                ]);
                return $response;
            }
            $response->setData([
                'success' => true,
                'data' => $serializer->serialize($form, 'json'),
            ]);
        }else{
            $response->setData([
                'success' => false,
                'error' => 'The request must be of type json',
                'data' => null,
            ]);
        }
        return $response;
    }

    #[Route('/delete/{id}', name: 'delete_product')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $entityManager->remove($product);
            $entityManager->flush();
            $response->setData([
                'success' => true,
                'data' => 'Product with id '.$product->getId().' deleted',
            ]);
        }else{
            $response->setData([
                'success' => false,
                'error' => 'The request must be of type json',
                'data' => null,
            ]);
        }
        return $response;
    }
}
