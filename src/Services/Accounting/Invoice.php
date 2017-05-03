<?php

namespace Myleshyson\LaravelQuickBooks\Services\Accounting;

use Myleshyson\LaravelQuickBooks\Contracts\QBResourceContract;
use Myleshyson\LaravelQuickBooks\Quickbooks;

class Invoice extends Quickbooks implements QBResourceContract
{
    public function create(array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();
        $this->resource = new \QuickBooks_IPP_Object_Invoice();
        $this->handleTransactionData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        return $this->service->add($this->context, $this->realm, $this->resource) ?: $this->service->lastError();
    }

    public function update($id, array $data)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();
        $this->resource = $this->find($id);

        $this->handleTransactionnData($data, $this->resource);
        isset($data['Lines']) ? $this->createLines($data['Lines'], $this->resource) : '';

        return $this->service->update($this->context, $this->realm, $id, $this->resource) ?: $this->service->lastError();
    }

    public function delete($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();
        return $this->service->delete($this->context, $this->realm, $id);
    }

    public function find($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();
        $query = $this->service->query($this->context, $this->realm, "SELECT * FROM Account WHERE Id = '$id' ");

        if (!empty($query)) {
            return $query[0];
        }
        return 'Looks like this id does not exist.';
    }

    public function generatePDF($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();

        if ($this->service->pdf($this->context, $this->realm, $id)) {
            header("Content-Disposition: attachment; filename=invoice_$id.pdf");
            header("Content-type: application/x-pdf");
            return print $this->service->pdf($this->context, $this->realm, $id);
        }
        return 'Looks like this id does not exist.';
    }

    public function generatePDFBlob($id)
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();

        if ($this->service->pdf($this->context, $this->realm, $id)) {
            return $this->service->pdf($this->context, $this->realm, $id);
        }
        return 'Looks like this id does not exist.';
    }

    public function get()
    {
        $this->service = new \QuickBooks_IPP_Service_Invoice();
        return $this->service->query($this->context, $this->realm, "SELECT * FROM Account");
    }
}
