Feature: Base controller
	In order to build a controller
	As a dev
	The controller needs to have a great set of basic features

Scenario: Parsing the url when there is only one route
	Given an implementation of BaseController
	And the route '/issues/:id' is present
	When the url '/issues/1' is parsed
	Then the controller for 'issues' should be loaded
	And the parameter 'id' should be '1'
	
Scenario: Parsing the url when there are two routes
	Given an implementation of BaseController
	And the route '/issues/:id' is present
	And the route '/projects/:id' is present
	When the url '/issues/1' is parsed
	Then the controller for 'issues' should be loaded
	And the parameter 'id' should be '1'


Scenario: Parsing a more complex url
	Given an implementation of BaseController
	And the route '/projects/:id' is present
	And the route '/projects/:id/issues/:issue' is present
	When the url '/projects/2/issues/3' is parsed
	Then the controller for 'projects' should be loaded
	And the parameter 'id' should be '2'
	And the parameter 'issue' should be '3'