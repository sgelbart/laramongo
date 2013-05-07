Feature: Checking the images characteristics like width, height and size.
    As an Administrador
    I would like write at log images that escape this patterns.

Scenario: Saving a product with images large than permitted
    Given I have a the product "simple_valid_product"
    Then should have 1 image name at log

Scenario: Saving a product with images small than permitted
    Given I have a the product "simple_valid_product"
    Then should don't have this product at log
