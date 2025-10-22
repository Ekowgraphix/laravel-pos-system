<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    /**
     * Get all records
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    /**
     * Create a new record
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update a record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Get paginated records
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15);
}
