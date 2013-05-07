Feature: Import Photos direct from existing website
    As an Administrador
    I would like import products images and categories images

Scenario: Saving a product
    Given I have the product "simple_valid_product"
    Then should get 1 photo

Scenario: Saving a category
    Given I have the category "valid_leaf_category"
    Then should get 1 photo
