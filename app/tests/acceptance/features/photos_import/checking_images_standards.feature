Feature: Checking the images characteristics like width, height and size.
    As an Administrador
    I would like write at log images that escape this patterns.

Scenario: Saving a product with images that meets the settings
    Given I have a the product "simple_valid_product"
    When I save the product
    Then should have 3 images with the right standards

Scenario: Saving a product with images that meets the settings but 2 images not meets the settings
    Given I have a the product "simple_valid_product"
    When I save the product
    Then should have 3 images with the right standards
    But 2 images don`t meets the standards
