<?php

namespace {namespace}\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * {{TableName}}Controller
 */
class {{TableName}}Controller extends ActionController {

	/**
	 * {{tableName}}Repository
	 *
	 * @var \{{namespace}}\Domain\Repository\{{TableName}}Repository
	 * @inject
	 */
	protected ${{tableName}}Repository = NULL;

	/**
	 * action list
     * @param string $ordering
	 * @return void
	 */
    public function listAction($ordering = 'tstamp') {
		${{tableName}}s = $this->{{tableName}}Repository->findAll($ordering);
		$this->view->assign('{{tableName}}s', ${{tableName}}s);
	}

	/**
	 * action new
	 *
	 * @param \{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}
	 * @ignorevalidation ${{tableName}}
	 * @return void
	 */
	public function newAction(\{{namespace}}\Domain\Model\{{TableName}} ${{tableName}} = NULL) {
		$this->view->assign('{{tableName}}', ${{tableName}});
	}

	/**
	 * action create
	 *
	 * @param \{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}
	 * @return void
	 */
	public function createAction(\{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}) {
		$this->addFlashMessage('{{TableName}} successfully created!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
		$this->{{tableName}}Repository->add(${{tableName}});
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}
	 * @ignorevalidation ${{tableName}}
	 * @return void
	 */
	public function editAction(\{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}) {
		$this->view->assign('{{tableName}}', ${{tableName}});
	}

	/**
	 * action update
	 *
	 * @param \{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}
	 * @return void
	 */
	public function updateAction(\{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}) {
        $this->addFlashMessage('{{TableName}} successfully saved!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
		$this->{{tableName}}Repository->update(${{tableName}});
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}
	 * @return void
	 */
	public function deleteAction(\{{namespace}}\Domain\Model\{{TableName}} ${{tableName}}) {
        $this->addFlashMessage('{{TableName}} successfully deleted!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
		$this->{{tableName}}Repository->remove(${{tableName}});
		$this->redirect('list');
	}

}
