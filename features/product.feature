Feature: Brochure
  verifies that the upload of a file

  Scenario:  upload a file

    When I send a POST request to "/product" as HTML form with body:
    | object | name | value |
    | file | brochure | public/uploads/brochure/4e5211bbb8667c0ecac5abfffa6ef99a.jpeg|
    And print response
