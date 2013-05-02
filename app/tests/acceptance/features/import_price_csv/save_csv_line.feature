Feature: SaveCsvLine
As an Administrator
I would like that a line of the csv file to be inserted into database
In order to update the price change of a product one store

Scenario: Importing a simple line into database
    Given I have an empty "temp_storesProductsIntegration" collection
    And I have the following line in csv:
        | TIPO    |   SUBTIPO    |   SECAO  |   SUBSECAO | COD_FILIAL  |    LM       |  COD_BARRA          |   PRC_ACONSELHADO |  PRC_PROMOCIONAL |  DT_INI_PROM    |    DT_FIM_PROM    |    PRC_FND_SECAO  |    ESTQ_REAL    |  TOP    |  EMBALAGEM  |  UNIDADE  | DATA_AVS |
        | 1       |   114        |    4     |     401    |    4        |   88045335  |    07899009094492   |  9.01             |          ''      |                 |                   |     9.9           | 0               |   1     |     1       |   UN      |    ''    |
    When I process the line
    Then I should have the following Price into database:
        """
             {
                lm: 88045335,
                unidade: 'un',
                stores: [
                    contagem: {
                        top: 1,
                        background_section: 9.9,
                        recommended_retail_price: 9.01
                    }
                ]
            }
        """

Scenario: Importing a line with pack qtd into database
    Given I have an empty "temp_storesProductsIntegration" collection
    And I have the following line in csv:
        | TIPO    |   SUBTIPO    |   SECAO  |   SUBSECAO | COD_FILIAL   |    LM       |  COD_BARRA          |   PRC_ACONSELHADO |  PRC_PROMOCIONAL |  DT_INI_PROM    |    DT_FIM_PROM    |    PRC_FND_SECAO  |    ESTQ_REAL    |  TOP    |  EMBALAGEM  |  UNIDADE  | DATA_AVS |
        | 1       |   114        |    4     | 401        |    27        |   88045356  |    07899009094508   |  11               |       9.9        |                 |                   |    11.9           | 0               |   1     |     1.5     |   M2      |          |
    When I process the line
    Then I should have the following Price into database:
        """
             {
                lm: 88045356,
                unidade: 'm2',
                pack: 1.5,
                stores: [
                    sorocaba: {
                        top: 1,
                        promotional_price: 9.9
                        background_section: 11.9,
                        recommended_retail_price: 11
                    }
                ]
            }
        """
