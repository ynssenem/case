<?php

namespace App\Tests;

use App\Service\AbortService;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbortResponseTest extends TypeTestCase
{
    public function testString(): void
    {
        $abortService = new AbortService();
        $res = $abortService->error("Hello World!");
        $this->assertInstanceOf(JsonResponse::class, $res);
    }
}
