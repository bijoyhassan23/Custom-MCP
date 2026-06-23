<?php

require_once __DIR__ . '/log.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'), true);

log_message("Received request: " . json_encode($request));

$method = $request['method'] ?? '';

if ($method === 'initialize') {
    $response = [
        "jsonrpc" => "2.0",
        "id" => $request['id'],
        "result" => [
            "protocolVersion" => "2025-11-25",
            "serverInfo" => [
                "name" => "Custom-MCP",
                "version" => "1.0.0"
            ],
            "capabilities" => [
                "tools" => new stdClass()
            ]
        ]
    ];

} elseif ($method === 'tools/list') {
    $response = [
        "jsonrpc" => "2.0",
        "id" => $request['id'],
        "result" => [
            "tools" => [
                [
                    "name" => "hello_world",
                    "description" => "Returns a hello message",
                    "inputSchema" => [
                        "type" => "object",
                        "title" => "hello_world_input",
                        "properties" => new stdClass(),
                        "additionalProperties" => false
                    ]
                ],
                [
                    "name" => "add_numbers",
                    "description" => "Add two numbers",
                    "inputSchema" => [
                        "type" => "object",
                        "title" => "add_numbers_input",
                        "properties" => [
                            "a" => [
                                "type" => "number",
                                "description" => "First number"
                            ],
                            "b" => [
                                "type" => "number",
                                "description" => "Second number"
                            ]
                        ],
                        "required" => ["a", "b"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]
    ];
} elseif ($method === 'tools/call') {

    $toolName = $request['params']['name'] ?? '';

    if ($toolName === 'hello_world') {

        $response = [
            "jsonrpc" => "2.0",
            "id" => $request['id'],
            "result" => [
                "content" => [
                    [
                        "type" => "text",
                        "text" => "Hello This is my MCP!"
                    ]
                ]
            ]
        ];

    } elseif ($toolName === 'add_numbers') {

        $args = $request['params']['arguments'] ?? [];

        $a = $args['a'] ?? 0;
        $b = $args['b'] ?? 0;

        $response = [
            "jsonrpc" => "2.0",
            "id" => $request['id'],
            "result" => [
                "content" => [
                    [
                        "type" => "text",
                        "text" => (string)($a + $b)
                    ]
                ]
            ]
        ];

    }
} else {
    $response = [
        "jsonrpc" => "2.0",
        "id" => $request['id'] ?? null,
        "error" => [
            "code" => -32601,
            "message" => "Method not found"
        ]
    ];
}

echo json_encode($response);