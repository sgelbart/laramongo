Feature: Checking if the products is indexing at Search Engine
As an Administrador
I would like to add a product and see if was indexed

Scenario: Saving a product
    Given I have a the product "simple_valid_product"
    Then should have indexed product at Search Engine
