<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace quasiris\CategoryStorage\Communication\Plugins\Event\Listener;

use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Generated\Shared\Transfer\ProductAbstractTransfer;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use quasiris\CategoryStorage\Communication\Controllers\SenderController;
use quasiris\CategoryStorage\Communication\Controllers\MixedController;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacade getFacade()
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 */
class KacperProductCategoryListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;
    private $sender;
    private $URL_TO_API;

    public function __construct($URL_TO_API) {
        $this->sender = new SenderController();
        $this->mixed = new MixedController();
        $this->URL_TO_API = $URL_TO_API;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ProductAbstractTransfer $eventTransfer
     *
     * @return void
     */
    public function handleBulk(array $CategoryTransfer, $eventName)
    {
        $productInfo = [];
        $concrete = [];

        foreach ($CategoryTransfer as $eventTransfer) {
            $productInfo[] = $eventTransfer->toArray();
        }

        //get all information about product 
        $get_data = $this->mixed->getCorrectAbstractConcreteProductIds($productInfo, true);
        $id = $get_data['id'];
        $type = $get_data['type'];

        foreach($id as $i) {
            $abstract = $this->getFactory()->getProductFacede()->findProductAbstractById($i)->toArray();
            $getConcreteProductsByAbstractProductId = $this->getFactory()->getProductFacede()->getConcreteProductsByAbstractProductId($i);
                    
            foreach ($getConcreteProductsByAbstractProductId as $eventTransfer) {
                if($eventTransfer->toArray() !== null && $eventTransfer->toArray() !== '') {
                    $concrete[] = $eventTransfer->toArray();
                }
            }

            //get category information
            $locale = $this->getFactory()->getLocaleFacede()->getCurrentLocale();
            $categories = $this->mixed->getProductCategories($i, $locale);
            
            $data = $this->mixed->createArrayToSend(
                'KacperProductCategoryListener',
                $eventName,
                $abstract,
                $concrete,
                $categories,
                $i
            );

            $this->sender->getDataFromApi($data, $this->URL_TO_API);
        }
    }
}
