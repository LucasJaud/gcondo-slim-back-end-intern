<?php

namespace App\Services;

use App\Http\Error\HttpNotFoundException;
use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Unit;
use App\Services\CondominiumService;

class UnitService
{
    public function __construct(protected CondominiumService $condominiumService) {}

    public function list()
    {
        $units = Unit::with('condominium')->get();

        return $units;
    }

    public function listByCondominium(int $condominiumId)
    {
        $this->validateCondominium($condominiumId);

        $units = Unit::where('condominium_id', $condominiumId)->with('condominium')->get();

        return $units;
    }

    public function find(int $id): Unit
    {
        $unit = Unit::with('condominium')->find($id);

        if (!$unit) {
            throw new HttpNotFoundException('Unit not found');
        }

        return $unit;
    }

    public function create(array $data): Unit
    {
        $this->validateUnitData($data);

        $this->validateCondominium($data['condominium_id']);

        $unit = Unit::create([
            'condominium_id' => $data['condominium_id'],
            'name' => $data['name'],
            'square_meters' => $data['square_meters'] ?? null,
            'bedroom_count' => $data['bedroom_count'] ?? null
        ]);

        return $unit;
    }

    public function update(int $id, array $data): Unit
    {
        $unit = $this->find($id);

        $this->validateUnitData($data);
        $this->validateCondominium($data['condominium_id']);

        $unit->fill([
            'name' => $data['name'],
            'square_meters' => $data['square_meters'] ?? null,
            'bedroom_count' => $data['bedroom_count'] ?? null,
            'condominium_id' => $data['condominium_id']
        ]);

        $unit->save();

        return $unit;
    }

    public function delete(int $id): bool
    {
        $unit = $this->find($id);

        $deleted = $unit->delete();

        return $deleted;
    }

    /** @throws HttpNotFoundException */
    private function validateCondominium(int $condominiumId): void
    {
        $this->condominiumService->find($condominiumId);
    }

    /** @throws HttpUnprocessableEntityException */
    private function validateUnitData(array $data)
    {
        if (empty($data['condominium_id'])) {
            throw new HttpUnprocessableEntityException('Condominium ID is required');
        }

        if (empty($data['name'])) {
            throw new HttpUnprocessableEntityException('Name is required');
        }

        // Validate square_meters if provided
        if (isset($data['square_meters']) && !is_null($data['square_meters'])) {
            if (!is_numeric($data['square_meters']) || $data['square_meters'] <= 0) {
                throw new HttpUnprocessableEntityException('Square meters must be a positive number');
            }
        }

        // Validate bedroom_count if provided
        if (isset($data['bedroom_count']) && !is_null($data['bedroom_count'])) {
            if (!is_numeric($data['bedroom_count']) || $data['bedroom_count'] < 0 || $data['bedroom_count'] != (int)$data['bedroom_count']) {
                throw new HttpUnprocessableEntityException('Bedroom count must be a non-negative integer');
            }
        }
    }
}
