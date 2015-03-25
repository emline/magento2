<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Model\Resource;


use Magento\Framework\Model\AbstractModel;

class EntitySnapshot
{
    /**
     * @var array
     */
    protected $snapshotData = [];

    /**
     * @var EntityMetadata
     */
    protected $entityMetadata;

    public function __construct(
        EntityMetadata $entityMetadata
    ) {
        $this->entityMetadata = $entityMetadata;
    }

    /**
     * @param AbstractModel $entity
     * @return void
     */
    public function registerSnapshot(AbstractModel $entity)
    {
        $data = array_intersect_key($entity->getData(), $this->entityMetadata->getFields($entity));
        $this->snapshotData[get_class($entity)][$entity->getId()] = $data;
    }

    /**
     * Check is current entity has changes, by comparing current object state with stored snapshot
     *
     * @param AbstractModel $entity
     * @return bool
     */
    public function isModified(AbstractModel $entity)
    {
        if (!$entity->getId()) {
            return true;
        }
        if (!isset($this->snapshotData[get_class($entity)][$entity->getId()])) {
            return true;
        }
        $data = array_intersect_key($entity->getData(), $this->entityMetadata->getFields($entity));
        if ($data !== $this->snapshotData[get_class($entity)][$entity->getId()]) {
            return true;
        }
        return false;
    }
}
