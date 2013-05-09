Feature: Searching all contents at website like products, categories etc.
As a client
I would like to search something at search_box

Scenario: Searching something
    Given I have a the product "simple_valid_product"
    And I have the category "valid_leaf_category"
    When I type at search "coisa"
    Then I should see products and categories related
