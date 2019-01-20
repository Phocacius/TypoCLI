
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    '{{company}}.' . $_EXTKEY,
    'web',   // Make module a submodule of 'web'
    '{{tablename}}', // Submodule key
    '',                     // Position
    array(
        '{{TableName}}' => 'list',

    ),
    array(
        'access' => 'user,group',
        'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/{{tablename}}-module.png',
        'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_{{tablename}}.xlf',
    )
);
