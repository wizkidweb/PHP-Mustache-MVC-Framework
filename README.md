# Mustache PHP MVC System

This is an easy-to-use PHP MVC framework using the Mustache templating engine.

## Adding Pages
It's easy to add a page to your MVC website system.  Simply create a PHP document in the **controller** folder with the name of your page, appended with *Controller.class.php*.
For example, if your page is called *about*, then the controller PHP file should be called `aboutController.class.php`.

Once you've created that file, create a class with the same name as your file, minus the word *class*, and extend the `baseController` class.

```php
class aboutController extends baseController {
	
}
```
	
You are required to have only one method, called `index`, so you can add that now.

```php
class aboutController extends baseController {
	
	public function index() {
		// Your code here
	}
	
}
```

Congratulations!  You've made a page using the PHP Mustache MVC Engine!  It doesn't do anything though.  Let's give it something to do.
You can set template variables for the Mustache engine in the index method using `$this->registry->Template->foo = "bar"`.  Then initialize the chosen template with `$this->registry->Template->show('template_name')`.  That's all it takes!

```php
class aboutController extends baseController {
	
	public function index() {
		// Set page title
		$this->registry->Template->page_title = "Welcome";
		
		// Set page template
		$this->registry->Template->show('index');
	}
	
}
```
	
### The Registry
The Registry is a way for your MVC application to access many of the features of the PHP Mustache MVC.  It can be accessed in your controllers with `$this->registry`.  Database access, templating systems, models, configuration, and controller information is stored here.  You can add and remove things from the Registry, which allows the rest of the application to access them.
	
## JavaScript access with AJAX
This framework works hand-in-hand with JavaScript and AJAX, with each controller natively supporting AJAX POST calls.  To receive an AJAX call, simply add the `onAjax()` method to your controller class:

```php
class aboutController extends baseController {
	
	// public function index() goes here
	
	function onAjax() {
		// Your code here
	}
}
```

You can then access the `$_POST` variables associated with your AJAX call.  The `index()` method will not be called if an AJAX call is detected.

## Database Access
Another class located on the Registry is the `DBase` class.  This provides a safe and secure way to connect to the PHP Mustache MVC's MySQL database.  You can access it using `$this->registry->DBase`.  While PHP Mustache MVC does not require a MySQL database, it is recommended for more advanced web applications.

The `DBase` class has two methods: `Query()` and `NonQuery()`.  Use `Query()` if you want the database to return a value, and `NonQuery()` if you are simply sending data.  Each will return `true` or `false`, depending on the success of the Query (or non-query).

Both methods clean incoming variables, so if you want to pass a variable, use `?` in the query line, *s*, *i*, or *f* in the second parameter, and each variable as a separate parameter in left-to-right order following.  Here's an example of using NonQuery with this system:

```php
$email = "test@example.com";
$qry = $this->registry->DBase->NonQuery("INSERT INTO users ('email') VALUES (?)", "s", $email);
```

With the `Query()` method, an array is returned with the values requested.

## Models
You can add your own classes as models to interpret data from the database or add additional functionality.  To do so, simply add your model class to the `model` folder, and append it with `.class.php`.  For example, if you have a model class called `myModel`, the file would be `myModel.class.php`.  It will automatically be added to the registry for use in your controllers.

## Mustache Templating Engine
This framework uses the Mustache templating engine.  To learn more about it, go to this link: https://mustache.github.io/mustache.5.html
