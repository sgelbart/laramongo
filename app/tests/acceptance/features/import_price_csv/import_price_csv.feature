Feature: ImportPriceCsv
As an Administrator
I would like to import a csv file containing price and product availability
In order to track the price changes of products per store

Scenario: Importing a full StoreProduct csv file
    Given I have no StoreProduct into database
    When The system import the "full-price-file.txt"
    Then I should have the following StoreProducts into database:
        | productA_price |
        | productB_price |
        | productC_price |

Scenario: Importing a partial prices csv file
    Given I have no StoreProduct into database
    When The system import the "partial-price-file.txt"
    Then I should have the following StoreProducts into database:
        | productB_price |
        | productC_price |

Scenario: Importing a partial prices csv file
    Given I have no StoreProduct into database
    And I have the following StoreProducts into database:
        | productA_price     |
        | productB_old_price |
    When The system import the "partial-price-file.txt"
    Then I should have the following StoreProducts into database:
        | productB_price |
        | productC_price |
    And I should Not have the following Price into database:
        | productB_old_price |
