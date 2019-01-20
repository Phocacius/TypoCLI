
    '{{column_name}}' => array(
        'exclude' => 1,
        'label' => '{{ColumnName}}',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            '{{columnName}}',
            array('maxitems' => 1),
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        ),
    ),