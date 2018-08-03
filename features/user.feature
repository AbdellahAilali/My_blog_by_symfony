
Feature: User
  Permet de vérifier que la class UserController fonctionne

  Scenario: load all the users

    When I send a GET request to "/userAll"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "root[0].id" should be equal to "025caf9e-e6e6-4aac-a45b"
    And the JSON node "root[0].firstname" should be equal to "John"
    And the JSON node "root[0].lastname" should be equal to "Doe"
    And the JSON node "root[0].birthday" should be equal to "2018-08-03"
    And the JSON node "root[0].comments[0].title" should be equal to "Le voyage de Chihiro"
    And the JSON node "root[0].comments[0].comment" should be equal to "Une fillette de 10 ans, prise au piège dans une maison sur la plage hantee par des esprits et des fantômes, doit combattre sorcières et dragons"

    And the JSON node "root[1].id" should be equal to "32132dsf132ds1f3ds21fsd"
    And the JSON node "root[1].firstname" should be equal to "Abdellah"
    And the JSON node "root[1].lastname" should be equal to "Ailali"
    And the JSON node "root[1].birthday" should be equal to "2018-08-03"
    And the JSON node "root[1].comments[0].title" should be equal to "Le château ambulant"
    And the JSON node "root[1].comments[0].comment" should be equal to "La jeune Sophie, âgée de 18 ans, travaille sans relâche dans la boutique de chapelier que tenait son père avant de mourir. Lors de l'une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"

    And the JSON node "root[2].id" should be equal to "4eb298dd-5cd7-4d10-9b9q"
    And the JSON node "root[2].firstname" should be equal to "Malik"
    And the JSON node "root[2].lastname" should be equal to "Ben"
    And the JSON node "root[2].birthday" should be equal to "2018-08-03"
    And the JSON node "root[2].comments[0].title" should be equal to "Kill Bill"
    And the JSON node "root[2].comments[0].comment" should be equal to "Condamnee à mort par son propre patron Bill, une femme-assassin survit à une balle dans la tête.  Quatre ans plus tard elle sort du coma et jure d’avoir sa vengeance.… "
    And the JSON should be equal to:
    """
    [
       {
          "id":"025caf9e-e6e6-4aac-a45b",
          "firstname":"John",
          "lastname":"Doe",
          "birthday":"2018-08-03",
          "comments":[
             {
                "title":"Le voyage de Chihiro",
                "comment":"Une fillette de 10 ans, prise au piège dans une maison sur la plage hantee par des esprits et des fantômes, doit combattre sorcières et dragons"
             }
          ]
       },
       {
          "id":"32132dsf132ds1f3ds21fsd",
          "firstname":"Abdellah",
          "lastname":"Ailali",
          "birthday":"2018-08-03",
          "comments":[
             {
                "title":"Le château ambulant",
                "comment":"La jeune Sophie, âgée de 18 ans, travaille sans relâche dans la boutique de chapelier que tenait son père avant de mourir. Lors de l'une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"
             }
          ]
       },
       {
          "id":"4eb298dd-5cd7-4d10-9b9q",
          "firstname":"Malik",
          "lastname":"Ben",
          "birthday":"2018-08-03",
          "comments":[
             {
                "title":"Kill Bill",
                "comment":"Condamnee à mort par son propre patron Bill, une femme-assassin survit à une balle dans la tête.  Quatre ans plus tard elle sort du coma et jure d’avoir sa vengeance.… "
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
