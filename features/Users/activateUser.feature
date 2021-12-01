Feature: As a patient I want to activate my account

  Background:
    Given there are patients in system:
      | id                                   | firstName | lastName   | email             | pesel       | gender | createdBy                            | activationTokenId                    |
      | c61b9a21-d30d-4ec4-a193-63821acf5e81 | Kacper    | Czajkowski | kacper@patient.pl | 84101144272 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 | 74d2eda5-b9ec-4ab3-802a-f8a73e4f048f |

  Scenario: Activate account
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "GET" request to "open/activate/74d2eda5-b9ec-4ab3-802a-f8a73e4f048f"
    Then the response status code should be 200

  Scenario: Attempting activate not existing uuid
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "GET" request to "open/activate/13042560-88bf-4f62-aa75-f5d4c3565476"
    Then the response status code should be 400
