Feature: Calculates price per region and send to
As an Administrator
I would like to import a csv file containing price and product availability
In order to track the price changes of products per store

Scenario: Importing a full StoreProduct csv file
    Given I have no StoreProduct into database
    And I have the following Products into database:
        | Product_B |
        | Product_C |
    When The system import the "partial-price-file.txt"
    Then I should have the following StoreProducts into database:
        | productB_price |
        | productC_price |
    And The following Product prices
        |  Product  | basePrice  | promotionalPrice |
        | Product_B |    11.00   |    11.00         |
        | Product_C |    11.98   |    11.98         |
