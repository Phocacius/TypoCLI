
    '{{column_name}}' => array(
        'exclude' => 1,
        'label' => '{{ColumnName}}',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectSingle',
        'items' => array(
            array('', 0),
            ),
            'foreign_table' => 'tx_{{ext}}_domain_model_{{foreignTable}}',
            'foreign_table_where' => 'AND tx_{{ext}}_domain_model_{{foreignTable}}.pid=###CURRENT_PID###',
        ),
    ),