<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

abstract class Controller
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         title="LEDGERS TASK - GUZMAN",
     *         version="1.0.0",
     *         description="This is the API documentation for my Task.",
     *         @OA\Contact(
     *             name="API Support",
     *             email="no-support@example.com"
     *         ),
     *         @OA\License(
     *             name="MIT",
     *             url="https://opensource.org/licenses/MIT"
     *         )
     *     ),
     *     @OA\Server(
     *         url="https://laravel.test/v1",
     *         description="Main API server"
     *     )
     * )
     */
}
