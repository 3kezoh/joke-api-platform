<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

final class JwtDecorator implements OpenApiFactoryInterface
{
  public function __construct(
    private OpenApiFactoryInterface $decorated
  ) {
  }

  public function __invoke(array $context = []): OpenApi
  {
    $openApi = ($this->decorated)($context);
    $schemas = $openApi->getComponents()->getSchemas();

    $schemas['Token'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'token' => [
          'type' => 'string',
          'readOnly' => true,
        ],
      ],
    ]);
    $schemas['Credentials'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'email' => [
          'type' => 'string',
          'example' => 'user@localhost',
        ],
        'password' => [
          'type' => 'string',
          'example' => 'password',
        ],
      ],
    ]);

    $pathItem = new Model\PathItem(
      ref: 'JWT Token',
      post: new Model\Operation(
        operationId: 'postCredentialsItem',
        tags: ['Authentication'],
        responses: [
          '200' => [
            'description' => 'Get JWT token',
            'content' => [
              'application/json' => [
                'schema' => [
                  '$ref' => '#/components/schemas/Token',
                ],
              ],
            ],
          ],
        ],
        summary: 'Get JWT token to login.',
        requestBody: new Model\RequestBody(
          description: 'Generate new JWT Token',
          content: new \ArrayObject([
            'application/json' => [
              'schema' => [
                '$ref' => '#/components/schemas/Credentials',
              ],
            ],
          ]),
        ),
        security: [],
      ),
    );
    $openApi->getPaths()->addPath('/login', $pathItem);

    return $openApi;
  }
}
