<?php

namespace Myleshyson\LaravelQuickBooks\Services\Accounting;

use Myleshyson\LaravelQuickBooks\Contracts\QBResourceContract;
use Myleshyson\LaravelQuickBooks\Quickbooks;

class QB_Class extends Quickbooks implements QBResourceContract
{
    public function create(array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Class();
        $this->resource = new \QuickBooks_IPP_Object_Class();
        $this->handleTransactionData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        if ($res = $this->service->add($this->context, $this->realm, $this->resource)) {
            return $res;
        } else {
            throw new \Exception($this->service->lastError($this->context));
        }
    }

    public function update($id, array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Class();
        $this->resource = $this->find($id);

        $this->handleTransactionData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        return parent::_update($this->context, $this->realm, \QuickBooks_IPP_IDS::RESOURCE_CLASS, $this->resource, $id) ?: $this->service->lastError();
    }

    public function delete($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Class();
        return parent::_update($this->context, $this->realm, \QuickBooks_IPP_IDS::RESOURCE_Class, $id);
    }

    public function find($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Class();
        $query = $this->service->query($this->context, $this->realm, "SELECT * FROM Class WHERE Id = '$id' ");
        if (!empty($query)) {
            return $query[0];
        }
        return 'Looks like this id does not exist.';
    }

    public function get()
    {
        $this->service = new \QuickBooks_IPP_Service_Class();
        return $this->service->query($this->context, $this->realm, "SELECT * FROM Class");
    }
}
