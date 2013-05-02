Feature: Calculating the product's price
    As an Administrador
    I would like to calculate the product's price

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded one mode.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.30           |         0         |
        |           1.20           |           1.60           |        1.40       |
        |           1.60           |             0            |        1.50       |
        |           1.60           |           1.30           |         0         |
        |           1.40           |           1.45           |        1.35       |

    When run the calculation

    Then should get for the region:
        | from_price  | to_price |
        |     1.30    |   1.30   |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded one mode with promotial_price.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.80           |         0         |
        |           1.20           |           1.60           |        1.40       |
        |           1.60           |             0            |        1.50       |
        |           1.60           |           1.30           |         0         |
        |           1.40           |           1.45           |        1.30       |

    When run the calculation

    Then should get for the region:
        | from_price  | to_price |
        |     1.45    |   1.30   |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded one mode with differents 'From'.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.80           |         0         |
        |           1.20           |           1.60           |        1.30       |
        |           1.60           |             0            |        1.50       |
        |           1.60           |           1.40           |         0         |
        |           1.40           |           1.45           |        1.30       |

    When run the calculation

    Then should get for the region:
        | from_price | to_price  |
        |     1.60   |   1.30    |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded one mode with differents 'From' but 'From' is smaller than 'To'.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.65           |         0         |
        |           1.20           |           1.20           |        1.30       |
        |           1.60           |              0           |        1.50       |
        |           1.60           |           1.60           |         0         |
        |           1.40           |           1.25           |        1.30       |

    When run the calculation

    Then should get for the region:
        | from_price  | to_price |
        |     1.25    |   1.30   |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded two modes and get the smallest mode.
     Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.30           |         0         |
        |           1.20           |           1.60           |        1.40       |
        |           1.60           |              0           |        1.50       |
        |           1.60           |           1.30           |         0         |
        |           1.40           |           1.45           |        1.40       |

    When run the calculation

    Then should get for the region:
        | from_price  | to_price |
        |     1.30    |   1.30   |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should not found mode and use the second smallest value.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.30           |         0         |
        |           1.20           |           1.60           |        1.35       |
        |           1.60           |              0           |        1.50       |
        |           1.60           |           1.55           |         0         |
        |           1.40           |           1.45           |        1.40       |

    When run the calculation

    Then should get for the region:
        | from_price | to_price |
        |     1.60   |   1.35   |

Scenario: Having one product which appeared at 5 stores numbered from 1 to 5, in this case should be founded.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.30           |         0         |
        |           1.20           |           1.20           |        1.35       |
        |           1.60           |              0           |        1.50       |
        |           1.60           |           1.55           |         0         |
        |           1.40           |           1.45           |        1.40       |

    When run the calculation

    Then should get for the region:
        | from_price | to_price |
        |     1.20   |   1.35   |

Scenario: Having one product which appeared at 2 stores numbered from 1 to 2, in this case should not be founded modes and get the second smallest value.
    Given the stores which has the following prices:
        | recommended_retail_price | background_section_price | promotional_price |
        |           1.0            |           1.30           |         0         |
        |           1.20           |           1.60           |        1.35       |

    When run the calculation

    Then should get for the region:
        | from_price | to_price |
        |     1.60   |   1.35   |
