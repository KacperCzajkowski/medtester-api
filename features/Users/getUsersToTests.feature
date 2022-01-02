Feature: As a laboratory worker I want to create new patient

  Background:
    Given there are laboratories in system:
      | id                                   | name         | createdBy                            |
      | ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9 | Lab number 1 | 9b7c792f-09f9-4a68-b5c1-3894d56f940b |
    And there are laboratory workers is system:
      | id                                   | laboratoryId                         | firstName | lastName   | email         | createdBy                            | pesel       | gender |
      | 145a22a7-e98b-4dfb-88cb-13ab130056e3 | ade2c510-f9d1-4c1f-8b76-8fba0e7a16a9 | Kacper    | Czajkowski | kacper@lab.pl | f9d89550-d592-4c54-88e4-8a590c16649a | 83072347336 | MALE   |
    And there are patients in system:
      | id                                   | firstName | lastName   | email             | pesel       | gender | createdBy                            |
      | c61b9a21-d30d-4ec4-a193-63821acf5e81 | Kacper    | Czajkowski | kacper@patient.pl | 84101144272 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |
      | e0754426-8da0-4155-af2d-224d73bede5d | Karol     | Jakis      | karol@patient.pl  | 59070494615 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |
      | a24d3504-f346-4b7a-9015-409ab7fc5a9f | Essa      | Mister     | essa@patient.pl   | 97080927455 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |
      | 10887c76-c780-49e2-9589-a110a399adaa | Major     | SPZ        | major@patient.pl  | 75072926572 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |

  Scenario: Create new patient by laboratory worker
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "GET" request to "lab-worker/users"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
      {
        "fullName": "Kacper Czajkowski",
        "pesel": "84101144272"
      },
      {
        "fullName": "Karol Jakis",
        "pesel": "59070494615"
      },
      {
        "fullName": "Essa Mister",
        "pesel": "97080927455"
      },
      {
        "fullName": "Major SPZ",
        "pesel": "75072926572"
      }
    ]
    """

  Scenario: Create new patient by laboratory worker
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "GET" request to "lab-worker/users?query=er"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
      {
        "fullName": "Kacper Czajkowski",
        "pesel": "84101144272"
      },
      {
        "fullName": "Essa Mister",
        "pesel": "97080927455"
      }
    ]
    """

  Scenario: Create new patient by laboratory worker
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "GET" request to "lab-worker/users?query=97"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
      {
        "fullName": "Essa Mister",
        "pesel": "97080927455"
      }
    ]
    """