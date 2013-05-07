Feature: Success log for exel import
    As an Administrator
    I would like to record the list of successfully imported itens when importing an excel file
    In order to have the record for future needs

Scenario: After fine importing
    Given I have the category "valid_leaf_category"
    When I import the "new_products.xlsx"
    Then I should see the import report with "4" success

Scenario: After failed importing
    Given I have the category "valid_leaf_category"
    When I import the "messed_up_products.xlsx"
    Then I should see the import report with "2" success
