<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\LineOrder;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order', name: 'order_')]
class OrderController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function listOrders(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $orders = $em->getRepository(Order::class)->findBy(['user' => $user], ['dateOrder' => 'DESC']);

        return $this->render('pages/order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function orderDetails(Order $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
    
        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to view details of this order.');
        }
    
        $lineOrders = $order->getLineOrders();
    
        return $this->render('pages/order/details.html.twig', [
            'order' => $order,
            'lineOrders' => $lineOrders,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $cart = $session->get('cart', []);
    
        if ($cart === []) {
            $this->addFlash('message', 'Your cart is empty');
            return $this->redirectToRoute('home');
        }
    
        $user = $this->getUser();
    
        // Recherchez une commande en cours de paiement pour cet utilisateur
        $existingOrder = $em->getRepository(Order::class)->findOneBy([
            'user' => $user,
            'statutOrders' => ['ORDER_PENDING']
        ]);
    
        if ($existingOrder && !in_array('ORDER_PAID', $existingOrder->getStatutOrders(), true)) {
            // Vérifiez si la commande existante a une référence et si elle est différente de celle générée par uniqid()
            dump('Existing Order Reference: ' . $existingOrder->getReference());
    
            if (!$existingOrder->getReference() || $existingOrder->getReference() !== uniqid()) {
                // Mettez à jour la référence uniquement si elle est différente
                $existingOrder->setReference(uniqid());
                $em->flush();
    
                dump('Reference Updated to: ' . $existingOrder->getReference());
            } else {
                dump('Using Existing Order without Updating Reference');
            }
    
            // Utilisez la commande existante qui n'a pas été payée
            $order = $existingOrder;
        } else {
            // Créez une nouvelle commande seulement si aucune commande en cours n'est trouvée
            $order = new Order();
            $order->setUser($user);
            $order->setReference(uniqid()); // ou utilisez une logique appropriée pour générer une référence unique
            $order->setStatutOrders(['ORDER_IN_PROCESS']);
    
            dump('New Order Created with Reference: ' . $order->getReference());
    
            // Ajoutez des lignes de commande pour chaque élément du panier
            foreach ($cart as $item => $quantity) {
                $product = $productRepository->find($item);
    
                $lineOrder = new LineOrder();
                $lineOrder->setProduct($product);
                $lineOrder->setSellingPrice($product->getPriceVAT());
                $lineOrder->setQuantity($quantity);
    
                $order->addLineOrder($lineOrder);
            }
    
            $total = $order->calculateTotal();
            $order->setTotal($total);
    
            $em->persist($order);
            $em->flush();
        }
    
        $this->addFlash('message', 'Commande mise à jour avec succès');
    
        return $this->render('pages/order/pay.html.twig', [
            'order' => $order,
            'lineOrders' => $order->getLineOrders(),
        ]);
    }
    

    #[Route('/pay/{id}', name: 'pay')]
    public function pay(Order $order = null, SessionInterface $session, EntityManagerInterface $em): Response
    {
        if (!$order) {
            $this->addFlash('error', 'Order not found.');
            return $this->redirectToRoute('cart_index');
        }

        $order->setStatutOrders(['ORDER_PAID']);

        $em->persist($order);
        $em->flush();

        $session->remove('cart');

        $this->addFlash('message', 'Payment successful.');

        return $this->redirectToRoute('order_details', ['id' => $order->getId()]);
    }

    #[Route('/cancel/{id}', name: 'cancel', methods: ['POST'])]
    public function cancel(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to cancel this order.');
        }

        if (!in_array('ORDER_CANCELED', $order->getStatutOrders(), true)) {
            $order->setStatutOrders(['ORDER_CANCELED']);

            $em->persist($order);
            $em->flush();

            $this->addFlash('message', 'Commande annulée avec succès.');
        } else {
            $this->addFlash('message', 'Cette commande ne peut pas être annulée.');
        }

        return $this->redirectToRoute('order_list');
    }

    #[Route('/cancel_payment/{id}', name: 'cancel_payment', methods: ['GET'])]
    public function cancelPayment(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to cancel payment for this order.');
        }

        // Mettez à jour le statut de la commande
        $order->setStatutOrders(['ORDER_PENDING']);

        $em->persist($order);
        $em->flush();

        $this->addFlash('message', 'Payment canceled successfully.');

        // Redirigez l'utilisateur vers le panier en cours
        return $this->redirectToRoute('cart_index');
    }
}