<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace quasiris\CategoryStorage\Communication\Plugins\Event\Subscriber;

use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;

use quasiris\CategoryStorage\Communication\Plugins\Event\Listener\KacperProductCategoryListener;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 */
class KacperProductCategorySubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    private $URL_TO_API;

    public function __construct($URL_TO_API) {
        $this->URL_TO_API = $URL_TO_API;
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $this->addCategoryCreateCategoryListener($eventCollection);
        $this->addCategoryUpdateCategoryListener($eventCollection);
        $this->addCategoryDeleteCategoryListener($eventCollection);

        //category to product
        $this->assignedCategoryToProductListener($eventCollection);
        $this->unassignedCategoryToProductListener($eventCollection);
        

        return $eventCollection;
    }

    //create
    protected function addCategoryCreateCategoryListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_AFTER_CREATE, new KacperProductCategoryListener($this->URL_TO_API));
    }

    //update
    protected function addCategoryUpdateCategoryListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_AFTER_UPDATE, new KacperProductCategoryListener($this->URL_TO_API));
    }

    //delete 
    protected function addCategoryDeleteCategoryListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_AFTER_DELETE, new KacperProductCategoryListener($this->URL_TO_API));
    }

    //assigned prodcut to category
    protected function assignedCategoryToProductListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_ASSIGNED, new KacperProductCategoryListener($this->URL_TO_API));
    }

    //unasigned prodcut to category
    protected function unassignedCategoryToProductListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_UNASSIGNED, new KacperProductCategoryListener($this->URL_TO_API));
    }
}