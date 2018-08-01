
Feature: User
  Permet de vérifier que la class UserController fonctionne

  Scenario: load all the users

    When I send a GET request to "/userAll"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "root[0].id" should be equal to "32132dsf132ds1f3ds21fsd"
    And the JSON node "root[0].firstname" should be equal to "Abdellah"
    And the JSON node "root[0].lastname" should be equal to "Ailali"
    And the JSON node "root[0].birthday" should be equal to "2018-08-01"
    And the JSON node "root[0].comments[0].title" should be equal to "Le chateau ambulant"
    And the JSON node "root[0].comments[0].comment" should be equal to "ma description"

    And the JSON should be equal to:
    """
      [
         {
        "id":"32132dsf132ds1f3ds21fsd",
        "firstname":"Abdellah",
        "lastname":"Ailali",
        "birthday":"2018-08-01",
        "comments":[
           {
              "title":"Le chateau ambulant",
              "comment":"ma description"
           }
                   ]
         }
      ]
    """

  Scenario: load the user by id

    When I send a GET request to "/user/32132dsf132ds1f3ds21fsd"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "root.firstname" should be equal to "Abdellah"
    And the JSON node "root.lastname" should be equal to "Ailali"
    And the JSON node "root.comments[0].title" should be equal to "Le chateau ambulant"
    And the JSON node "root.comments[0].comment" should be equal to "ma description"
    And the JSON should be equal to:
    """
       {
          "firstname":"Abdellah",
          "lastname":"Ailali",
          "comments":[
             {
                "title":"Le chateau ambulant",
                "comment":"ma description"
             }
          ]
       }
    """

  Scenario: error load the user by id

    When I send a GET request to "/user/32132dsf132ds1fs21fsd"
    And the JSON should be equal to:
     """
       {

       }
    """
    Then the response status code should be 404


  Scenario: delete user by id

    When I send a DELETE request to "/user_delete/32132dsf132ds1f3ds21fsd"
    Then the response status code should be 200
    Then the response should be in JSON
    When I send a GET request to "32132dsf132ds1f3ds21fsd"
    Then the response status code should be 404

  Scenario: delete user by id

    When I send a DELETE request to "/user_delete/32132dsf132dss21fsd"
    Then the response status code should be 404



  Scenario: create new user

    When I send a POST request to "/user" with body:
    """
    {
      "id":"fs555cesmp93scscdz6",
        "firstname":"Mohamed",
        "lastname":"ALi",
        "birthday":"1994-07-31"
    }
    """
  Scenario: error create new user

    Then the response status code should be 200
    Then the response should be in JSON
    When I send a POST request to "/user" with body:
     """
    {
      "id":"fs555cesmp93scscdz6",
        "birthday":"1994-07-31"
    }
    """
    Then the response status code should be 400


  Scenario: modify user by id

    When I send a PUT request to "/user/modify/32132dsf132ds1f3ds21fsd" with body:
    """
    {
      "id":"32132dsf132ds1f3ds21fsd",
        "firstname":"Malik",
        "lastname":"Bentalah",
        "birthday":"1993-07-31"
    }
    """

    Then the response status code should be 200
    Then the response should be in JSON

  Scenario: error modify user by id

    When I send a PUT request to "/user/modify/32132dsf1f3ds21fsd" with body:
     """
    {
      "id":"32132dsf1f3ds21fsd",
        "firstname":"Malik",
        "lastname":"Bentalah",
        "birthday":"1993-07-31"
    }
    """
    Then the response status code should be 404