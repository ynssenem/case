<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\AbortService;
use App\Service\ValidatorService;
use App\Validator\CreateOrderValidator;
use App\Validator\GetOrderValidation;
use App\Validator\UpdateOrderValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/api")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/order", methods="PUT")
     */
    public function bulkCreateOrderAction(Request $request, UserInterface $user, ValidatorService $validator, AbortService $abortService, ManagerRegistry $managerRegistry): JsonResponse
    {
        $orderValidation = new CreateOrderValidator(json_decode($request->getContent(), true));

        if ($errors = $validator->validate($orderValidation)) {
            return $abortService->errors($errors);
        }

        if ($managerRegistry->getManager()->getRepository(Order::class)->hasOrderCode($orderValidation->getOrderCode())) {
            return $abortService->error("Order Code available!", $orderValidation->getOrderCode());
        }

        $orderEntity = new Order();
        $orderEntity->setOrderCode($orderValidation->getOrderCode())
            ->setProductId($orderValidation->getProductId())
            ->setQuantity($orderValidation->getQuantity())
            ->setAddress($orderValidation->getAddress())
            ->setShippingDate($orderValidation->getShippingDate())
            ->setProvider($user);

        $em = $managerRegistry->getManager();
        $em->persist($orderEntity);
        $em->flush();

        return $this->json([
            "message" => "Success",
            "orderId" => $orderEntity->getId()
        ]);
    }

    /**
     * @Route("/order", methods="PATCH")
     */
    public function bulkUpdateOrderAction(Request $request, ValidatorService $validator, AbortService $abortService, ManagerRegistry $managerRegistry, UserInterface $user): JsonResponse
    {
        $orderValidation = new UpdateOrderValidator(json_decode($request->getContent(), true));

        if ($errors = $validator->validate($orderValidation)) {
            return $abortService->errors($errors);
        }

        $orderEntity = $managerRegistry->getRepository(Order::class)->findOneBy([
            "id" => $orderValidation->getOrderId(),
            "provider" => $user
        ]);

        if (!$orderEntity instanceof Order) {
            return $abortService->error("Not Found Order Id", $orderValidation->getOrderId(), Response::HTTP_NOT_FOUND);
        }

        if ($orderEntity->getShippingDate()) {
            return $abortService->error("ShippingDate data cannot be updated", $orderValidation->getOrderId(), Response::HTTP_BAD_REQUEST);
        }

        $orderEntity->setShippingDate($orderValidation->getShippingDate());

        $em = $managerRegistry->getManager();
        $em->persist($orderEntity);
        $em->flush();

        return $this->json("Order Updated");
    }

    /**
     * @Route("/getOrder", methods="GET")
     */
    public function getOrderAction(Request $request, ValidatorService $validator, AbortService $abortService, ManagerRegistry $managerRegistry, UserInterface $user): JsonResponse
    {
        $getOrderValidator = new GetOrderValidation([
            "orderId" => (int)$request->query->get("orderId")
        ]);

        if ($errors = $validator->validate($getOrderValidator)) {
            return $abortService->errors($errors);
        }

        $orderEntity = $managerRegistry->getRepository(Order::class)->findOneBy([
            "id" => $getOrderValidator->getOrderId(),
            "provider" => $user
        ]);

        if (!$orderEntity instanceof Order) {
            return $abortService->error("Not Found Order Id", $getOrderValidator->getOrderId(), Response::HTTP_NOT_FOUND);
        }

        return $this->json($orderEntity);
    }

    /**
     * @Route("/getOrders", methods="GET")
     */
    public function getOrdersAction(AbortService $abortService, ManagerRegistry $managerRegistry, UserInterface $user): JsonResponse
    {
        $orderEntity = $managerRegistry->getRepository(Order::class)->findBy([
            "provider" => $user
        ], [
            "id" => "DESC"
        ]);

        if (!is_array($orderEntity) || empty($orderEntity)) {
            return $abortService->error("Not Found Orders", "", Response::HTTP_NOT_FOUND);
        }

        return $this->json($orderEntity);
    }
}