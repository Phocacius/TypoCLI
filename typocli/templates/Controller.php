<?php

namespace {{namespace}}\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * {{TableName}}Controller
 */
class {{TableName}}Controller extends ActionController {

    /**
     * action list
     *
     * @return void
     */
    public function listAction() {
        $returnUrl = urlencode($_SERVER['REQUEST_URI']);

        $db = $GLOBALS['TYPO3_DB'];
        $pid = GeneralUtility::_GET('id');
        if(!$pid) {
            $pid = 0;
        }
        $entry = $db->exec_SELECTquery('uid', 'tx_{{ext}}_domain_model_{{tablename}}', 'pid = '.$pid)->fetch_array();

        if($entry !== NULL) {
            $params = ['edit[tx_{{ext}}_domain_model_{{tablename}}]['.$entry['uid'].']' => 'edit'];
        } else {
            $params = ['edit[tx_{{ext}}_domain_model_{{tablename}}]['.$pid.']' => 'new'];
        }

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uri = $uriBuilder->buildUriFromRoute('record_edit', $params);
        $this->redirectToUri($uri);
    }

}
