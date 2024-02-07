<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Schema;
use ApiPlatform\OpenApi\OpenApi;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {

    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['logged_user'] = [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer'
                ],
                'username' => [
                    'type' => 'string'
                ],
                'roles' => [
                    'type' => 'array'
                ]
            ]
        ];

        $openApi->getPaths()->addPath(
            '/api/logout',
            new PathItem(
                get: new Operation(
                    operationId: 'api_logout',
                    tags: ['Auth'],
                    summary: 'Logout',
                    description: 'Logout user',
                    responses: [
                        '204' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'null'
                                    ]
                                ]
                            ]
                        ]
                    ]
                )
            )
        );

        $openApi->getPaths()->addPath(
            '/api/login',
            new PathItem(
                post: new Operation(
                    operationId: 'api_login',
                    tags: ['Auth'],
                    summary: 'Login',
                    description: 'Login user',
                    requestBody: new RequestBody(
                        content: new ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => [
                                        'username', 'password'
                                    ],
                                    'properties' => [
                                        'username' => [
                                            'type' => 'string'
                                        ],
                                        'password' => [
                                            'type' => 'string'
                                        ]
                                    ]
                                ]
                            ]
                        ])
                    ),
                    responses: [
                        '200' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/logged_user'
                                    ]
                                ]
                            ]
                        ],
                        '401' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'error' => [
                                                'type' => 'string',
                                                'example' => 'Invalid credentials.'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                )
            )
        );

        return $openApi;
    }
}