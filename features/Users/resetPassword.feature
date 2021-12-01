Feature: As a patient I want to activate my account

  Background:
    Given there are patients in system:
      | id                                   | firstName | lastName   | email             | pesel       | gender | createdBy                            |
      | c61b9a21-d30d-4ec4-a193-63821acf5e81 | Kacper    | Czajkowski | kacper@patient.pl | 84101144272 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |

  Scenario: Reset password
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "open/reset-password" with body:
    """
    {
      "email": "kacper@patient.pl"
    }
    """
    Then the response status code should be 200

