Feature: As a user I want to fetch all my tests results

  Background:
    Given there are laboratories in system:
      | id                                   | name         | createdBy                            |
      | 8ca6459a-d293-4366-ad9a-92f7e56a1915 | Lab number 1 | 9b7c792f-09f9-4a68-b5c1-3894d56f940b |
    And there are laboratory workers is system:
      | id                                   | laboratoryId                         | firstName | lastName   | email         | createdBy                            | pesel       | gender |
      | 145a22a7-e98b-4dfb-88cb-13ab130056e3 | 8ca6459a-d293-4366-ad9a-92f7e56a1915 | Kacper    | Czajkowski | kacper@lab.pl | f9d89550-d592-4c54-88e4-8a590c16649a | 83072347336 | MALE   |
    And there are patients in system:
      | id                                   | firstName | lastName   | email             | pesel       | gender | createdBy                            |
      | c61b9a21-d30d-4ec4-a193-63821acf5e81 | Kacper    | Czajkowski | kacper@patient.pl | 84101144272 | MALE   | 145a22a7-e98b-4dfb-88cb-13ab130056e3 |


  Scenario: As a laboratory worker I want to create new tests result for user
    Given "kacper@lab.pl" is logged in using "test1234" password
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/lab-worker/test/create" with JSON headers and body:
    """
    {
      "userPesel": "84101144272"
    }
    """
    And the response status code should be 200
    Then I send a "POST" request to "/lab-worker/test" with JSON headers and body:
    """
    {
      "status": "in-progress",
      "results": [
        {
          "name": "Morfologia",
          "icdCode": "ICD-9: C55",
          "indicators": [
            {
              "name": "Leukocyty",
              "result": 7.93,
              "unit": "tys/mm3",
              "referenceRange": {
                "min": 4.2,
                "max": 9.0
              }
            },
            {
              "name": "Erytrocyty",
              "result": 5.50,
              "unit": "mln/mm3",
              "referenceRange": {
                "min": 4.6,
                "max": 6.1
              }
            },
            {
              "name": "Hemoglobina",
              "result": 16.8,
              "unit": "g/dl",
              "referenceRange": {
                "min": 13.0,
                "max": 18.0
              }
            }
          ]
        }
      ]
    }
    """
    Then the response status code should be 200
