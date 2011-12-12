Feature: Routing
	In order to match a url
	As a dev
	The Router needs to have an easy route matching API

Scenario: Parsing a matching url with a single parameter
	Given a new Router with the URL '/issues/1'
	When the route '/issues/:id' is matched
	Then the Router should report success
	And the parameter 'id' should be '1'
	
Scenario: Parsing a non-matching url with a single parameter
	Given a new Router with the URL '/projects/1'
	When the route '/issues/:id' is matched
	Then the Router should report failure

Scenario: Parsing a more complex url
	Given a new Router with the URL '/projects/2/issues/3'
	When the route '/projects/:id/issues/:issue' is matched
	Then the Router should report success
	And the parameter 'id' should be '2'
	And the parameter 'issue' should be '3'

Scenario: Requesting non-existing parameters
	Given a matched Router
	When a non-existing parameter is requested from the Router
	Then the Router should return null

Scenario: Parsing the base url
	Given a new Router with the URL '/'
	When the route '/' is matched
	Then the Router should report success