Feature: As a laboratory worker I want to create new patient

  Background:
    Given there are admins in system:
      | id                                   | firstName | lastName   | email           | pesel       | gender |
      | a6c310ed-13a5-4e1a-9ad9-168911e2c2cf | Kacper    | Czajkowski | kacper@admin.pl | 84101144272 | MALE   |
    And there are laboratories in system:
      | id                                   | name         | createdBy                            |
      | ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9 | Lab number 1 | 9b7c792f-09f9-4a68-b5c1-3894d56f940b |
    And there are laboratory workers is system:
      | id                                   | laboratoryId                         | firstName | lastName   | email         | createdBy                            | pesel       | gender |
      | 145a22a7-e98b-4dfb-88cb-13ab130056e3 | ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9 | Kacper    | Czajkowski | kacper@lab.pl | f9d89550-d592-4c54-88e4-8a590c16649a | 83072347336 | MALE   |

  Scenario: Create new patient by laboratory worker
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "lab-worker/create" with body:
    """
      {
        "email": "email@test.pl",
        "roles": "ROLE_PATIENT",
        "firstName": "Kacper",
        "lastName": "Jakistam",
        "pesel": "99111909931",
        "gender": "FEMALE"
      }
    """
    Then the response status code should be 201

  Scenario: Create new patient by system admin
    Given "kacper@admin.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "lab-worker/create" with body:
    """
      {
        "email": "email@test.pl",
        "roles": "ROLE_PATIENT",
        "firstName": "Kacper",
        "lastName": "Jakistam",
        "pesel": "99111909931",
        "gender": "FEMALE"
      }
    """
    Then the response status code should be 201
#
#  Scenario: Create new laboratory worker by system admin
#    Given "kacper@admin.pl" is logged in using "test1234" password
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a "POST" request to "lab-worker/create" with body:
#    """
#      {
#        "email": "email@test.pl",
#        "roles": "ROLE_LABORATORY_WORKER",
#        "firstName": "Kacper",
#        "lastName": "Jakistam",
#        "pesel": "99111909931",
#        "gender": "FEMALE",
#        "laboratoryId": "ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9"
#      }
#    """
#    Then the response status code should be 201

  Scenario: Create new laboratory worker by other laboratory worker failure
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "lab-worker/create" with body:
    """
      {
        "email": "email@test.pl",
        "roles": "ROLE_LABORATORY_WORKER",
        "firstName": "Kacper",
        "lastName": "Jakistam",
        "pesel": "99111909931",
        "gender": "FEMALE"
      }
    """
    Then the response status code should be 201
