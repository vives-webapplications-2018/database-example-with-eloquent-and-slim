# Database communication with Slim and Eloquent

Note: Run MySQL queries from within VSCode with https://marketplace.visualstudio.com/items?itemName=formulahendry.vscode-mysql


## Create a new Slim project

```
composer create-project slim/slim-skeleton database-example
```

## Add Eloquent to your project

```
composer require illuminate/database
```

## Configure Eloquent

Add the following configuration to your `settings.php` file. Update the settings values depending on your system and MySQL server.

```php
'db' => [
	'driver' => 'mysql',
	'host' => 'localhost',
	'database' => 'database',
	'username' => 'user',
	'password' => 'password',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
]
```

## Setup Eloquent dependency

Add the following code to your `dependencies.php` file.

```php
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) {
    return $capsule;
};
```

## Setup a database and table

Use the following SQL scheme and data for this example:

```sql
CREATE DATABASE demo;
use demo;

CREATE TABLE todos(
	id int NOT NULL AUTO_INCREMENT,
	title VARCHAR(64) NOT NULL,
	description text,
	PRIMARY KEY(id)
);

INSERT INTO todos (title, description) VALUES
	('foo', 'lorem ipsum'),
	('bar', 'lipsum lorem'),
	('baz', 'ipsum lorem');
```

## Create a Model for your data

Create new directory in `src/` called `Models/`. In this directory you can create a new class with the following code:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model 
{
    public $timestamps = false;
}
```

The class must live in a namespace. In this example `App\Models` is used.

To inherit the important behaviour from Eloquent, our class must extend the `Illuminate\Database\Eloquent\Model` class.

The convention dictates that the the name of our class is the _singular_ and _camelcased_ variant of the name of the table that exists in the database. The table in our database is called `todos`, this results in a class name called `Todo`.

Our table does not contain the default `updated_at` and `created_at` attributes. To disable this functionality we must tell Eloquent that _timestamps_ are disabled with a `$timestamps = false` property in our class.

### Setup autoloading

In the `composer.json` file add the autoload configuration that tells the autoloader that all our files exist in the `src/` directory.

```
"autoload-dev": {
	"psr-4": {
		"Tests\\": "tests/",
		"App\\": "src/"
	}
},
```

Don't forget to create a new autoload file using the new settings with:

```
composer dumpautoload
```

## Using our Todo model

Now we are ready to use our Todo model wherever we need it in our code.

Note: The namespace is used at the top of the file with `use \App\Models\Todo;`.

### List all todo items

A static method `all()` is available on the Model objects. This will return an array of all results as a PHP object. All the properties defined in the database (`id`, `title` and `description`) are available as properties on that PHP object as well. This is all done automatically by Eloquent.

```php
$todos = Todo::all();

foreach ($todos as $todo) {
	echo "<p>$todo->title: <br>$todo->description</p>";
}
```

### Get a single todo item by its id

The `Todo` class provides an `find(id)` method to search for a single instance identified by its id.

```php
$todo = Todo::find(4);
```

### Create a new todo item

Just create a new instance of the `Todo` class. Assign new values to its properties and call the `save()` method when ready.

```php
$todo = new Todo();
$todo->title = "Hello from php script";
$todo->description = "cool hé";
$todo->save();
```

### Edit an todo item

Find or take an instance of `Todo` and update its properties. When ready just call the `save()` method.

```php
$todo = Todo::find(3);
$todo->title = "new title";
$todo->save();
```


### Delete an todo item

To delete an instance in the database just call the `delete()` method on an `Todo` instance, and all the associated data in the database will be gone.

```php
$old = Todo::find(4);
$old->delete();
```








Sources:

* https://www.slimframework.com/docs/v3/cookbook/database-eloquent.html
* https://laravel.com/docs/5.7/eloquent#eloquent-model-conventions
* https://marketplace.visualstudio.com/items?itemName=formulahendry.vscode-mysql
