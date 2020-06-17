<?php

namespace quasiris\CategoryStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use quasiris\CategoryStorage\CategoryStorageDependencyProvider;


class CategoryStorageCommunicationFactory extends AbstractCommunicationFactory
{
    public function getProductFacede() {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_PRODUCT);
    }
    public function getLocaleFacede() {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_LOCALE);
    }


}

