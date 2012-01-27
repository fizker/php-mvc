PHP MVC library
===============

This is a library of a few tools easing creating a REST-based MVC style
PHP web site.

There are three primary parts of the library:

1. \mvc\Router - Identifying routes and parsing parameters
2. \mvc\Controller - A controller base class that uses the aforementioned 
	router and have some convenience methods for handling POST, PUT and the
	other HTTP methods. It have some template-loading as well
3. Views - Controller expects sub-classes to return views, which is any
	object having a render() method that returns a string.
	
	The base Controller can automatically load and hydrate mustache templates,
	which lives up to the view requirement.


\mvc\Router
-----------

The Router is instantialized with an URL, and when asked if it matches a route,
it parses the route and applies the URL.

Routes looks like one of the following:

- `/`
- `/users`
- `/users/:id`
- `/users/:id/edit`
- `/users/:id/friends/:friend`

These can produce any of two parameters: id and friend. Both can be fetched by
the router after `$router->matches($route)` returns `true`, by calling
`$router->get($key)`. The `get($key)` method will return null if the parameter 
did not exist.

Keys can be updated or added by using `$router->set($key, $val)`.

The Router have another matching-algorithm, `$router->isStatic($patterns)`, 
where the patterns are regex-patterns. Some default patterns for javascript 
and css files can be retrieved by the three static functions;
`StaticController::getJSMatcher()`, `StaticController::getImgMatcher()` and 
`StaticController::getCSSMatcher()`.


URL Rewriting
-------------

For the router to work, the url must be rewritten. A sample `.htaccess` is
added to the repo, named `htaccess` (note, a `.` must be added to the front of
the file for it to work!).

This `.htaccess` file should be placed at the root of the site, and it will
redirect all trafic from that folder forward to the `index.php` file, in the
form of transforming `<root>/a/b` into `<root>/index.php?/a/b`.

The routing-framework can work its magic from this point forward.


\mvc\Controller
---------------

The abstract class `\mvc\Controller` supplies a number of convenience methods,
such as loading templates from the disk, identifying HTTP method
(`$this->is($method)`) and getting data for the method (`$this->getPost()`,
`$this->getPut()`, `$this->getGet()`).

It requires the `\mvc\Router` as the sole constructor argument, and exposes it
to subclasses as `$this->params`.

The method-data functions (`getPost()`, `getGet()`, `getPut()`) all returns 
arrays similar to `$_GET` and `$_POST`, which is simple key-value based arrays.


Code example
------------

This is an example of a simple API, which defines a few routes and sets up a
static controller for various javascript, css and image files.

First, setting up the router. Please note the `\mvc\requestController()`
function; it is supplied by the library as a convenience. What it does is
extract the URL provided by the included `.htaccess` file and hand it to a new
`\mvc\Router` instance. It takes a single callback-function as parameter:

	\mvc\requestController(function($router) {
		if($router->isStatic(
			array(
				\mvc\StaticController::getJSMatcher()
				, \mvc\StaticController::getCSSMatcher()
				, \mvc\StaticController::getImgMatcher()
			)))
		{
			return new \mvc\StaticController(__DIR__.'/static/', $router);
		}
		
		// This loads the list of blogs dependent on the filter level
		if($router->matches('/blogs')
			|| $router->matches('/blogs/:year')
			|| $router->matches('/blogs/:year/:month')
			|| $router->matches('/blogs/:year/:month/:day'))
		{
			return new \blogs\ListController($router);
		}
		
		// This loads and presents a single blog
		if($router->matches('/blogs/:year/:month/:day/:title')) {
			return new \blogs\ItemController($router);
		}
		
		// This matches /, /about, /contact, etc
		// It does not match /blogs, as that is already handled
		if($router->matches('/')
			|| $router->matches('/:page'))
		{
			return new PageController($router);
		}
	});

In the callback function, we create a number of Controller subclasses depending on the current
route match.

The \mvc\StaticController is supplied by the library. You point it at a folder,
and it loads and returns a physical file from that folder. In the example above,
the route `a/b.jpg` is turned into `static/a/b.jpg` on the disk drive.

The ListController, ItemController and PageController is application-specific.
They are mostly alike and only differs on where the data is pulled, so I will
only show one example:

	<?php
	namespace blogs\;
	
	define ('DIR_VIEWS', __DIR__.'/templates');
	
	class ListController extends \mvc\Controller {
		// The following two methods are optional
		// They should return an object with a render method
		// They default to an empty string
		public function getHeaderView() {
			return $this->loadTemplate('header');
		}
		public function getFooterView() {
			return $this->loadTemplate('footer');
		}
		
		// This function is required. 
		// It should return an object with a render method
		public function getView() {
			$query = 'SELECT * FROM blogs WHERE'
				.$this->getWhereClause()
				.' ORDER BY year DESC, month DESC, day DESC, title DESC';
			
			$sql = mysql_query($query);
			
			$blogs = array();
			while($row = mysql_fetch_assoc($sql)) {
				$blogs[] = $row;
			}
			
			return $this->loadTemplate('blogs/list', $blogs);
		}
		
		private function getWhereClause() {
			$year = $this->params->get('year');
			$month = $this->params->get('month');
			$day = $this->params->get('day');
			
			$where = '';
			
			if(!$year)
				return $where;
			
			$where .= " year='$year'";
			
			if(!$month)
				return $where;
			
			$where .= ' AND month='$month'";
			
			if(!$day)
				return $where;
			
			$where .= " AND day='$day'";
			
			return $where;
		}
	}
	?>

This should be fairly straight-forward. The only unknown is the `loadTemplate`
call. It can take two parameters: $templateName[, $data].

What it does is load a template inside the folder at path DIR_VIEWS with the
extension `.mustache`. It will hand the contents of the file off directly into
the [Mustache] parser.

For the example above, the templates-folder should look like this:

	./
	../
	blogs/
		list.mustache
		item.mustache
	header.mustache
	footer.mustache


License
-------

The code for this project is licensed under the [WTFPL](http://sam.zoy.org/wtfpl/).

The full text is:

	           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
	                   Version 2, December 2004
	
	Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>
	
	Everyone is permitted to copy and distribute verbatim or modified
	copies of this license document, and changing it is allowed as long
	as the name is changed.
	
	           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
	  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
	
	 0. You just DO WHAT THE FUCK YOU WANT TO.