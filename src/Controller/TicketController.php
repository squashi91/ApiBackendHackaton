<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\ProductRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/new', name: 'new_ticket')]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        SerializerInterface $serializer): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $ticket = new Ticket();
            $form = $this->createForm(TicketType::class, $ticket);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($ticket);
                $entityManager->flush();
                $response->setData([
                    'success' => true,
                    'data' => $serializer->serialize($ticket, 'json'),
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

    #[Route('/read/{id}', name: 'read_ticket')]
    public function read(Request $request, Ticket $ticket, SerializerInterface $serializer): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $response->setData([
                'success' => true,
                'data' => $serializer->serialize($ticket, 'json')
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

    #[Route('/delete/{id}', name: 'delete_ticket')]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $entityManager->remove($ticket);
            $entityManager->flush();
            $response->setData([
                'success' => true,
                'data' => 'Ticket with id '.$ticket->getId().' deleted',
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

    #[Route('/analytics', name: 'analytics_ticket')]
    public function analytics(Request $request,
                           ProductRepository $productRepository,
                           TicketRepository $ticketRepository,
                           EntityManagerInterface $entityManager): JsonResponse
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()){
            $all_products = $productRepository->findAll();
            $total_amount = 0;
            $sold_products_by_type = [];
            $sold_products_by_type['Laptops'] = 0;
            $sold_products_by_type['Pcs'] = 0;
            $sold_products_by_type['Phones'] = 0;
            $sold_products_by_type['Tablets'] = 0;
            $sold_products_by_type['Other'] = 0;
            $visa_tickets = $entityManager->createQueryBuilder()
                ->select('COUNT(t)')
                ->from('App\Entity\Ticket', 't')
                ->where('t.paymentType = :paymentType')
                ->setParameter('paymentType', 'Visa')
                ->getQuery()->getResult();
            $mastercard_tickets = $entityManager->createQueryBuilder()
                ->select('COUNT(t)')
                ->from('App\Entity\Ticket', 't')
                ->where('t.paymentType = :paymentType')
                ->setParameter('paymentType', 'Mastercard')
                ->getQuery()->getResult();
            foreach ($all_products as $product){
                $total_amount += $product->getPrice();
                foreach ($sold_products_by_type as $key => $sold_product_by_type){
                    if ($product->getProductType() === $key){
                        $sold_products_by_type[$key] += 1;
                    }
                }
            }
            $response_data = [
                'total_amount_products_sold' => $total_amount,
                'products_sold_by_type' => $sold_products_by_type,
                'tickets_payed_visa' => $visa_tickets,
                'tickets_payed_mastercard' => $mastercard_tickets,
            ];
            $response->setData([
                'success' => true,
                'data' => $response_data,
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
