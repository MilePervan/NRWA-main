<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="NRWA API Documentation",
 *      description="REST API dokumentacija za NRWA projekt",
 *      @OA\Contact(
 *          email="support@example.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Local development server"
 * )
 *
 * @OA\Tag(
 *      name="Users",
 *      description="Operacije nad korisnicima"
 * )
 *
 * @OA\Tag(
 *      name="Dispatchers",
 *      description="Operacije nad dispečerima"
 * )
 *
 * @OA\Tag(
 *      name="Managers",
 *      description="Operacije nad menadžerima"
 * )
 *
 * @OA\Tag(
 *      name="Locations",
 *      description="Operacije nad lokacijama"
 * )
 */


class SwaggerAnnotations
{
   
}

