
Feature: User
  Permet de vérifier que la class UserController fonctionne


  Scenario: load the user by id

    When I send a GET request to "/user/32132dsf132ds1f3ds21fsd"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "root.firstname" should be equal to "Abdellah"
    And the JSON node "root.lastname" should be equal to "Ailali"
    And the JSON node "root.comments[0].title" should be equal to "Le château ambulant"
    And the JSON node "root.comments[0].comment" should be equal to "La jeune Sophie, âgée de 18 ans, travaille sans relâche dans la boutique de chapelier que tenait son père avant de mourir. Lors de l'une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"
    And the JSON should be equal to:
    """
       {
          "firstname":"Abdellah",
          "lastname":"Ailali",
          "comments":[
             {
                "title":"Le château ambulant",
                "comment":"La jeune Sophie, âgée de 18 ans, travaille sans relâche dans la boutique de chapelier que tenait son père avant de mourir. Lors de l'une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"
             }
          ]
       }
    """

  Scenario: error load the user by id

    When I send a GET request to "/user/32132dsf11fs21fsd"
    Then the response status code should be 404
    And the JSON node "error_message" should be equal to "User not found"

  Scenario: create new user

    When I send a POST request to "/user" with body:
    """
    {
        "firstname":"Mohamed",
        "lastname":"ALi",
        "birthday":"1994-07-31"
    }
    """

    Then the response status code should be 200
    Then the response should be in JSON

  Scenario: error create new user
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
        "lastname":"Bentalah",
        "firstname":"Malik",
        "birthday":"1993-07-31"
    }
    """

    Then the response status code should be 200
    Then the response should be in JSON

  Scenario: error modify user by id

    When I send a PUT request to "/user/modify/32132dsf1f3ds21fsd"
    Then the response status code should be 400

  Scenario: delete user by id

    When I send a DELETE request to "/user_delete/32132dsf132ds1f3ds21fsd"
    Then the response status code should be 200
    Then the response should be in JSON
    When I send a GET request to "/user/32132dsf132ds1f3ds21fsd"
    Then the response status code should be 404

  Scenario: error delete user by id

    When I send a DELETE request to "/user_delete/32132dsf132dss21fsd"
    Then the response status code should be 404
