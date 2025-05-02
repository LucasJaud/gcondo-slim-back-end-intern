<?php 

namespace App\Services;

use App\Models\Venue;
use App\Services\CondominiumService;
use App\Http\Error\HttpNotFoundException;
use App\Http\Error\HttpUnprocessableEntityException;


class VenueService
{

    public function __construct(protected CondominiumService $condominiumService) {}

    public function list()
    {
        $venues = Venue::with('condominium')->get();

        return $venues;
    }

    public function listByCondominium(int $condominiumId)
    {
        $this->validateCondominium($condominiumId);

        $venues = Venue::where('condominium_id', $condominiumId)->with('condominium')->get();

        return $venues;
    }

    public function find(int $venueId)
    {
        $venue = Venue::with('condominium')->find($venueId);

        if (!$venue){
            throw new HttpNotFoundException('Venue not found');
        }

        return $venue;
    }

    public function create(array $data)
    {
        $this->validateCondominium($data['condominium_id']);
        $this->validateVenueData($data);

        $venue = Venue::create([
            'condominium_id' => $data['condominium_id'],
            'name' => $data['name'],
            'square_meters' => $data['square_meters'] ?? null,
            'maximum_occupancy' => $data['maximum_occupancy'] ?? null
        ]);

        return $venue;
    }

    public function update(int $id, array $data)
    {
        $venue = $this->find($id);
        $this->validateCondominium($data['condominium_id']);
        $this->validateVenueData($data);

        $venue->fill([
            'condominium_id' => $data['condominium_id'],
            'name' => $data['name'],
            'square_meters' => $data['square_meters'] ?? null,
            'maximum_occupancy' => $data['maximum_occupancy'] ?? null
        ]);

        $venue->save();

        return $venue;
    }

    public function delete(int $id): bool
    {
        $venue = $this->find($id);
        $deleted = $venue->delete();

        return $deleted;
    }

    private function validateVenueData(array $data)
    {
        if (empty($data['condominium_id'])){
            throw new HttpUnprocessableEntityException('Condominium ID is required');
        }

        if(empty($data['name'])){
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if(isset($data['maximum_occupancy']) && !is_null($data['maximum_occupancy'])){
            if(!is_numeric($data['maximum_occupancy']) || $data['maximum_occupancy'] < 0 || $data['maximum_occupancy'] != (int)$data['maximum_occupancy']){
                throw new HttpUnprocessableEntityException('Maximum occupancy must be a non-negative integer');
            }
        }

        if(isset($data['square_meters']) && !is_null($data['square_meters'])){
            if (!is_numeric($data['square_meters']) || $data['square_meters'] <= 0){
                throw new HttpUnprocessableEntityException('Square meters must be a positive number');
            }
        }

    }

    private function validateCondominium(int $condominiumId): void
    {
        $this->condominiumService->find($condominiumId);
    }
}


