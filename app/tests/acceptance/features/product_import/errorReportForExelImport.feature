Feature: Error reporting for exel import
    As an Administrator
    I would like to see an error reporting after importing an excel file
    In order to know how to fix and improve the file that is being imported

Scenario: After fine importing
    Given I have the category "valid_leaf_category"
    When I import the "new_products.xlsx"
    Then I should see the import report with "0" errors

Scenario: After failed importing
    Given I have the category "valid_leaf_category"
    When I import the "messed_up_products.xlsx"
    Then I should see the import report with "2" errors
