<?php

namespace Myleshyson\LaravelQuickBooks\Services\Accounting;

use Myleshyson\LaravelQuickBooks\Contracts\QBResourceContract;
use Myleshyson\LaravelQuickBooks\Quickbooks;

class TimeActivity extends Quickbooks implements QBResourceContract
{
    public function create(array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_TimeActivity();
        $this->resource = new \QuickBooks_IPP_Object_TimeActivity();
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
        $this->service = new \QuickBooks_IPP_Service_TimeActivity();
        $this->resource = $this->find($id);

        $this->handleTransactionData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        return $this->service->update($this->context, $this->realm, $id, $this->resource) ?: $this->service->lastError();
    }

    public function delete($id)
    {
        $this->service = new \QuickBooks_IPP_Service_TimeActivity();
        return $this->service->delete($this->context, $this->realm, $id);
    }

    public function find($id)
    {
        $this->service = new \QuickBooks_IPP_Service_TimeActivity();
        $query = $this->service->query($this->context, $this->realm, "SELECT * FROM TimeActivity WHERE Id = '$id' ");
        if (!empty($query)) {
            return $query[0];
        }
        return 'Looks like this id does not exist.';
    }

    public function get()
    {
        $this->service = new \QuickBooks_IPP_Service_TimeActivity();
        return $this->service->query($this->context, $this->realm, "SELECT * FROM TimeActivity");
    }
}
