Feature: Generate QR code via URL

  @javascript
  Scenario: Visiting URL
    Given I am on "/qr-code/test.png"
    And the response status code should be 200

  Scenario: Visiting URL
    Given I am on "/qr-code/test.png"
    And I should see "image/png" in the header "content-type"