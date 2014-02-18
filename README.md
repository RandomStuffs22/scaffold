#Documentation

IN PROGRESS

This is where you will find the documentation for Scaffold, we reccommend that you use the latest version of PHP when using this framework!

##Sections
####Complete
- <a href="#setting-up-routes">Setting up routes</a>
- <a href="#setting-up-a-controller">Setting up a controller</a>

####Incomplete
- Setting up a view
- Setting up a model
- Including a helper class
- Using core classes within your app
- Creating a helper class

###Setting up routes

First navigate to ```/config/routes.php``` you'll find the routes for your application here. Look at the below code:

```php
//  URL regex => controller/view/model to use.
$config['routes'] = array(
	//  Our error controller
	//  This not only handles 404s/403s, but also system errors.
	'error' => 'error',
	
	//  This is an example route for your index controller
	'index' => 'main'
);
```

This suggests that any visits to ```/``` or ```/index``` will be an instance of the ```main``` controller. If you wish to add a new route to a different controller, or even a specific method of the same controller just add another (key, value) pair into the array, example:

```php
//  URL regex => controller/view/model to use.
$config['routes'] = array(
	//  Our error controller
	//  This not only handles 404s/403s, but also system errors.
	'error' => 'error',
	
	//  This is an example route for your index controller
	'index' => 'main',
	'about' => 'main.about',
	'login' => 'login'
);
```

If the user was to visit ```yourappurl.com/login``` it would invoke the login controller, and if a user were to visit ```yourappurl.com/about```
it would invoke the main controller and run a method named ```about```. This allows you to control functionality and enables you to change which view you're showing. To keep everything simple just specifiying the controller name will also invoke a method ```index``` in our example which corresponds to the value set in ```/config/misc.php```

Routing in Scaffold is very customisable and will be covered later on within a "configuring your app" secton, though there are some comments which attempt to explain various things which you're able to do.

###Setting up a controller

The logical side of your application will be controlled by the controllers that you create, with the default installation we provide you with a starting controllre ```main``` which represents the index page of your application. In this section you will be shown how to produce an output from your controller and how to add another controller in which we will navigate to using the controller logic.

First navigate to ```/app/controllers/main.php``` in this file you should see something along the lines of:

```php
class Main_controller extends Controller {
	public function __construct() {
		parent::__construct();

		//  Set template data
		$this->template->set(array(
			'hello' => 'world'
		));
	}
	
	public function index() {
		$this->template->render('main');
	}
}
```

Any controller within Scaffold must extend the ```Controller``` class, contain a constructor that constructs it's parent (```parent::__construct()```) and contains the default index method defined in ```/config/misc.php``` as mentioned in the previous section.

Taking a look at what is actually happening inside the constructor, we're using the template object to set some values which we can pass on to the view. In this example, we're setting a variable ```$hello``` to the value ```world```, as this is in the constructor this variable will be accessible in any view that belongs to this constructor via the routes.

Then in our default ```index``` method we call ```$this->template->render('main');``` which renders the main view located in ```/app/views/main.php```

Taking a look at our standard view we will find some html and the use of our ```hello``` variable.

```php
<p><?php echo $hello; ?></p>
<p>Hi there, and welcome to the wonderful world of <a href="http://scaffold.im">Scaffold</a>! What you&rsquo;re looking at (that's me) is the default controller.</p>
<p>You'll find me hanging around <code>app/controllers/main.php</code>, and the view (that's the fancy HTML outputting bit) is in <code>app/views/main.php</code>.</p>

<h2>New here?</h2>
<p>You'll probably want to read <a href="http://learn.scaffold.im">the documentation</a> through first. Although Scaffold's pretty easy to pick up, looking at a codebase can be pretty overwhelming the first time. You'll also want to know Scaffold's hidden tips and tricks as well, no doubt.</p>
```

This can be edited like any standard html page and should only contain the dynamic content for your application, any static content should be handled elsewhere and will be covered later on in the documentation.

We'll next create a new controller for example, so create a new file ```test.php``` in the directory ```/app/controllers/``` it should contain the following code:

```php
<?php defined('IN_APP') or die('Get out of here'); // Stops unwanted visits

public class Test extends Controller {

	public function __construct() {
		parent::__construct();

		$this->template->set(array(
			'test' => 'Hello this is the testing controller'
		));
	}

	public function index() {
		$this->template->render('test');
	}
}

?>
```

Once you've done this, save it and create a new file ```test.php``` within ```/app/views/``` this file should contain the following:

```php
<p><?php echo $test; ?></p>
```

This should output the value we have sent to the template in the constructor, and to follow the rest of the framework architecture we suggest you create a new model (note: this will be covered later), to do so create a new file ```test.php``` in ```/app/models/```, it should contain the following code:

```php
<?php defined('IN_APP') or die('Get out of here');

class Test_model extends Model {
	public function __construct() {
		parent::__construct();
	}
}
?>
```
The next step in this documentation will detail how to properly set up a set of views.

##Contributions
We are open for contributions on this project, any requests or suggestions are welcome. 