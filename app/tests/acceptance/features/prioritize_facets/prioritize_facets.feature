Feature: PrioritizeFacets
As an Administrator
I would like to prioritize and hide facets
In order to give more relevancy to some characteristics of the products
And to help the visitors to find what they are looking for

Scenario: Prioritize facets of chave de entrada
    Given I have the category "leaf_with_facets"
    When I priorize the characteristics of "leaf_with_facets" like:
        | Capacidade | 15 |
        | Quantidade | 60 |
        | Coleção    | 30 |
        | Cor        | 80 |
    And I visit the "leaf_with_facets" category page
    Then I should see the facets in the following order:
        | Capacidade | 4 |
        | Quantidade | 2 |
        | Coleção    | 3 |
        | Cor        | 1 |

Scenario: Prioritize and hide facets of chave de entrada
    Given I have the category "leaf_with_facets"
    When I priorize the characteristics of "leaf_with_facets" like:
        | Capacidade | 80 |
        | Quantidade | 70 |
        | Coleção    | 30 |
        | Cor        | -3 |
    And I visit the "leaf_with_facets" category page
    Then I should see the facets in the following order:
        | Capacidade | 1 |
        | Quantidade | 2 |
        | Coleção    | 3 |
    And I shoul Not see the "Cor" facet
