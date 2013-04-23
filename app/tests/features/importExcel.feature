Feature: Import excel file
    As an Administrator
    I want to be able to import a excel file containing a list of products
    In order insert or update products of a chave de entrada

Scenario: Import new products to category
    Given I have the category "valid_leaf_category"
    When I import the "new_products.xlsx"
    Then I should get the four new producs into database

Scenario: Import and update existent products
    Given I have the category "valid_leaf_category"
    And I have the product "simple_valid_product"
    When I import the "new_products.xlsx"
    Then I should get the four new producs into database
