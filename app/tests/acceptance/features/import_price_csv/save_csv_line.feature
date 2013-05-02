Feature: SaveCsvLine
As an Administrator
I would like that a line of the csv file to be inserted into database
In order to update the price change of a product one store

Scenario: Importing a simple line into database
    Given I have no StoreProduct into database
    And I have the following line in csv:
        | TIPO    |   SUBTIPO    |   SECAO  |   SUBSECAO | COD_FILIAL  |    LM       |  COD_BARRA          |   PRC_ACONSELHADO |  PRC_PROMOCIONAL |  DT_INI_PROM    |    DT_FIM_PROM    |    PRC_FND_SECAO  |    ESTQ_REAL    |  TOP    |  EMBALAGEM  |  UNIDADE  | DATA_AVS |
        | 1       |   114        |    4     |     401    |    27       |   8800001   |    07899009094492   |    9.9            |         7.0      |                 |                   |     10.9          | 0               |   1     |     1       |   UN      |    ''    |
    When I process the line
    Then I should have the following StoreProduct into database:
        """
            {
                "_id": 8800001,
                "unit": "un",
                "pack": 1,
                "stores": {
                    "sorocaba": {
                        "top": 1,
                        "promotional_price": 7.0,
                        "background_section": 10.9,
                        "recommended_retail_price": 9.9
                    }
                }
            }
        """

Scenario: Importing a line with pack qtd into database
    Given I have no StoreProduct into database
    And I have the following line in csv:
        | TIPO    |   SUBTIPO    |   SECAO  |   SUBSECAO | COD_FILIAL   |    LM       |  COD_BARRA          |   PRC_ACONSELHADO |  PRC_PROMOCIONAL |  DT_INI_PROM    |    DT_FIM_PROM    |    PRC_FND_SECAO  |    ESTQ_REAL    |  TOP    |  EMBALAGEM  |  UNIDADE  | DATA_AVS |
        | 1       |   114        |    4     | 401        |    27        |   88045356  |    07899009094508   |  11               |       9.9        |                 |                   |    11.9           | 0               |   1     |     1.5     |   M2      |          |
    When I process the line
    Then I should have the following StoreProduct into database:
        """
             {
                "_id": 88045356,
                "unit": "m2",
                "pack": 1.5,
                "stores": {
                    "sorocaba": {
                        "top": 1,
                        "promotional_price": 9.9,
                        "background_section": 11.9,
                        "recommended_retail_price": 11
                    }
                }
            }
        """
