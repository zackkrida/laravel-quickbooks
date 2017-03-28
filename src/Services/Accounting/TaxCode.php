<?php

namespace Myleshyson\LaravelQuickBooks\Services\Accounting;

use Myleshyson\LaravelQuickBooks\Quickbooks;

class TaxCode extends Quickbooks
{
    public function find($id)
    {
        $this->service = new \QuickBooks_IPP_Service_TaxCode();
        $query = $this->service->query($this->context, $this->realm, "SELECT * FROM TaxCode WHERE Id = '$id' ");
        if (!empty($query)) {
            return $query[0];
        }
        return 'Looks like this id does not exist.';
    }

    public function get()
    {
        $this->service = new \QuickBooks_IPP_Service_TaxCode();
        return $this->service->query($this->context, $this->realm, "SELECT * FROM TaxCode") ?: $this->service->lastError();
    }
}
