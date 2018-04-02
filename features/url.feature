Feature: Generate QR code via URL

  Scenario: Visiting demo
    Given I am on "/qr-code/demo"
    Then the response status code should be 200

  Scenario: Visiting URL
    Given I am on "/qr-code/test.png"
    Then the response status code should be 200
    And I should see "image/png" in the header "content-type"