<?php

use Slim\Http\Request;
use Slim\Http\Response;

use \App\Models\Todo;

// Routes

$app->get('/todos', function (Request $request, Response $response, array $args) {


    // List all rows
    $todos = Todo::all();

    foreach ($todos as $todo) {
        echo "<p>$todo->title: <br>$todo->description</p>";
    }

    // Get single row
    $todo = Todo::find(4);

    // Create new row
    $todo = new Todo();
    $todo->title = "Hello from php script";
    $todo->description = "cool hé";
    $todo->save();

    // Edit existing row
    $todo = Todo::find(3);
    $todo->title = "new title";
    $todo->save();

    // Delete an row
    $old = Todo::find(4);
    $old->delete();

    return $response;
});
