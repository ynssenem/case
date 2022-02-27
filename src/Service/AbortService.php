<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class AbortService
{
    public function errors(ConstraintViolationListInterface $datas, int $code = 400): JsonResponse
    {
        $resData = [];

        foreach ($datas as $data) {
            if (!$data instanceof ConstraintViolationInterface) {
                continue;
            }

            array_push($resData, [
                "value" => $data->getPropertyPath(),
                "message" => $data->getMessage(),
                "code" => $data->getCode()
            ]);
        }

        return new JsonResponse($resData, $code);
    }

    public function error(string $message, $value = null, int $code = 400): JsonResponse
    {
        $resData = [];

        array_push($resData, [
            "value" => $value,
            "message" => $message,
            "code" => $code
        ]);

        return new JsonResponse($resData, $code);
    }
}