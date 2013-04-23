Feature: Import excel file
    As an Administrator
    I want to be able to import a excel file containing a list of products
    In order insert or update products of a category

Scenario: Import new products to category
    Given I have the category "valid_leaf_category"
    When I am importing the "new_products.xlsx"
    Then I should get the new producs into database
