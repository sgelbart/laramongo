Feature: Display Facets
As an visitor
I would like to see the facets for a category
In order to be able to filter the search by those

Scenario: Display facets of chave de entrada
    Given I have the category "leaf_with_facets"
    And a SearchEngine enabled for facets
    When I visit the "leaf_with_facets" category page
    Then I should see the facets:
        | Capacidade |
        | Quantidade |
        | Coleção    |
        | Cor        |
