<?php

namespace Myleshyson\LaravelQuickBooks\Services\Accounting;

use Myleshyson\LaravelQuickBooks\Contracts\QBResourceContract;
use Myleshyson\LaravelQuickBooks\Quickbooks;

class Term extends Quickbooks implements QBResourceContract
{
    public function create(array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Term();
        $this->resource = new \QuickBooks_IPP_Object_Term();
        $this->handleNameListData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        if ($res = $this->service->add($this->context, $this->realm, $this->resource)) {
            return $res;
        } else {
            throw new \Exception($this->service->lastError($this->context));
        }
    }

    public function update($id, array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Term();
        $this->resource = $this->find($id);

        $this->handleNameListData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        return parent::_update($this->context, $this->realm, \QuickBooks_IPP_IDS::RESOURCE_TERM, $this->resource, $id) ?: $this->service->lastError();
    }

    public function delete($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Term();
        return parent::_delete($this->context, $this->realm, \QuickBooks_IPP_IDS::RESOURCE_TERM, $id);
    }

    public function find($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Term();
        $query = $this->service->query($this->context, $this->realm, "SELECT * FROM Term WHERE Id = '$id' ");
        if (!empty($query)) {
            return $query[0];
        }
        return 'Looks like this id does not exist.';
    }

    public function get()
    {
        $this->service = new \QuickBooks_IPP_Service_Term();
        return $this->service->query($this->context, $this->realm, "SELECT * FROM Term");
    }
}
