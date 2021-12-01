Feature: As a laboratory worker I want to create new patient

  Background:
    Given there are admins in system:
      | id                                   | firstName | lastName   | email           | pesel       | gender |
      | a6c310ed-13a5-4e1a-9ad9-168911e2c2cf | Kacper    | Czajkowski | kacper@admin.pl | 84101144272 | MALE   |
    And there are laboratory workers is system:
      | id                                   | laboratoryId                         | firstName | lastName   | email         | createdBy                            | pesel       | gender |
      | 145a22a7-e98b-4dfb-88cb-13ab130056e3 | ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9 | Kacper    | Czajkowski | kacper@lab.pl | f9d89550-d592-4c54-88e4-8a590c16649a | 83072347336 | MALE   |
    And there are patients in system:
      | id                                   | firstName | lastName   | email             | pesel       | gender | createdBy                            |
      | c61b9a21-d30d-4ec4-a193-63821acf5e81 | Kacper    | Czajkowski | kacper@patient.pl | 84101144272 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |

  Scenario: As a patient I want to change my email
    Given "kacper@patient.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "current-user/change-email" with body:
    """
      {
        "newEmail": "nowy@patient.pl"
      }
    """
    Then the response status code should be 200

  Scenario: As a lab worker I want to change my password
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "current-user/change-email" with body:
    """
      {
        "newEmail": "nowy@lab.pl"
      }
    """
    Then the response status code should be 200

  Scenario: As an admin I want to change my password
    Given "kacper@admin.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "current-user/change-email" with body:
    """
      {
        "newEmail": "nowy@admin.pl"
      }
    """
    Then the response status code should be 200
