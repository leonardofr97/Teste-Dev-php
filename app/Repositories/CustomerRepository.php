<?php

namespace App\Repositories;

use App\Customer;
use Ramsey\Uuid\Type\Integer;

class CustomerRepository
{
    public function find(int $per_page, int $page, array $filters)
    {
        $query = Customer::Query();
        foreach ($filters as $column => $conditions) {
            foreach ($conditions as $operator => $value) {
                if ($value) {
                    switch ($operator) {
                        case 'inc':
                            $query->where($column, 'LIKE', '%' . $value . '%');
                            break;
                        case 'eq':
                            $query->where($column, '=', $value);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $query->paginate($per_page);
    }

    public function findById(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $customer = $this->findById($id);
        $customer->update($data);
        return $customer;
    }

    public function delete(int $id)
    {
        $customer = $this->findById($id);
        $customer->delete();
    }
}
