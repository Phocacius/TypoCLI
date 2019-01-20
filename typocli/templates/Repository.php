<?php
namespace {{namespace}}\Domain\Repository;

/**
 * The repository for {{TableName}}s
 */
class {{TableName}}Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    public function findAll($ordering = 'tstamp') {
        $query = parent::createQuery();
        $query->setOrderings(array(
            $ordering => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        ));
        return $query->execute();
    }
}
