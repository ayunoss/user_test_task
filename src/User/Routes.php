<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Src\Database;

$group->group('/user', function (RouteCollectorProxy $router) {

    $router->get('/', function (Request $request, Response $response, array $args) {
        $db = new Database();
        $data = $db->select('SELECT * FROM Users');
        $message = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($message);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $router->get('/{id}', function (Request $request, Response $response, array $args) {
        $db = new Database();
        $id = (int)$args['id'];
        $data = $db->select("SELECT * FROM Users WHERE id='{$id}'");
        $message = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($message);
        return $response;
    });

    $router->post('/create', function (Request $request, Response $response, array $args) {
        $db = new Database();
        $data = $request->getParsedBody();
        $sql = "INSERT INTO Users (id, username, first_name, last_name)
        VALUES (null, :username, :first_name, :last_name)";
        $result = $db->insert($sql, $data);
        $message = json_encode($result);
        $response->getBody()->write($message);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $router->put('/{id}', function (Request $request, Response $response, array $args) {
        $db = new Database();
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $result = $db->update($data, $id);
        $messageRaw = $result === 1 ? ['message' => 'success'] : ['error' => 'user not found'];
        $message = json_encode($messageRaw);
        $response->getBody()->write($message);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $router->delete('/{id}', function (Request $request, Response $response, array $args) {
        $db = new Database();
        $id = (int)$args['id'];
        $sql = "DELETE FROM Users WHERE id='{$id}'";
        $rowCount = $db->delete($sql);
        $messageRaw = $rowCount === 1 ? ['message' => 'success'] : ['error' => 'user not found'];
        $message = json_encode($messageRaw);
        $response->getBody()->write($message);
        return $response->withHeader('Content-Type', 'application/json');
    });
});

