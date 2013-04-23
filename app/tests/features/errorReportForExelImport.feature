Feature: Error reporting for exel import
    As an Administrator
    I would like to see an error reporting after importing an excel file
    And roll back in case of error
    In order to know how to fix and improve the file that is being imported

Scenario: After fine importing
    Given I have the category "valid_leaf_category"
    When I import the "new_products.xlsx"
    Then I should see the import report of "new_products"
    Then I should get the four new producs into database

Scenario: After failed importing
    Given I have the category "valid_leaf_category"
    When I import the "messed_up_products.xlsx"
    Then I should see the import report of "messed_up_products"
    Then I should get no products into database
