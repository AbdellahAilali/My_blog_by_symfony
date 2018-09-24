Feature: Comment
  Permet de vérifier que la class CommentController fonctionne


  Scenario: create new comment

    When I send a POST request to "/comment" with body:
    """
    {
     "title":"la decouvert du continent ameriquain",
     "description":"par dessus les collines et les riviere",
     "user": "025caf9e-e6e6-4aac-a45b"
    }
    """
    Then the response status code should be 200
    Then the response should be in JSON
    Then print response

  Scenario: error create new comment empty

    When I send a POST request to "/comment" with body:
    """
    {
     id":"cfp5kdff85545",
     "title":"la decouvert du continent ameriquain"
    }
    """
    Then the response status code should be 400
    Then the response should be in JSON


  Scenario: modify a comment

    When I send a PUT request to "/modify_comment/654984ds65f1d651f6s5d1f" with body:
    """
    {
     "title":"roots",
     "description":"les decouvertes africaine dans le temps"
    }
    """
    Then the response status code should be 200
    Then the response should be in JSON


  Scenario: error modify comment by id, I give a bad id

    When I send a PUT request to "/modify_comment/654984ds65f1d651f6s"
    Then the response status code should be 400


   Scenario: delete comment by id

     When I send a DELETE request to "/delete_comment/654984ds65f1d651f6s5d1f"
     Then the response status code should be 200
     Then the response should be in JSON

  Scenario: error delete comment by id,  I give a bad id

    When I send a DELETE request to "/delete_comment/654984ds65f1d651f6csccs"
    Then the response status code should be 404
    Then the response should be in JSON
