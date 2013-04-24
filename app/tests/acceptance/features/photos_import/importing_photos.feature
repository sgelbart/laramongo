Feature: Import Photos direct from existing website
    As an Administrador
    I would like import products images and categories images

Scenario: Saving a product
    Given I have the product "simple_valid_product"
    When I save the product
    Then should get 3 photos

Scenario: Saving a category
    Given I have the category "valid_leaf_category"
    When I save the category
    Then should get 1 photo
