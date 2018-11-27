Feature: caching test

  Scenario: Get user list when users are not registered in cache

    Given users are not cached
    When I send a GET request to "/cached"
    And  the response status code should be 200
    Then users are in cache


  Scenario:  Get user list when users are registered in cache

    Given save users in cache
    When I send a GET request to "/cached"
    And the response status code should be 200
    Then users are in cache
    Then doctrine doesn't not query


