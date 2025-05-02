<?php 

namespace App\Services;

use App\Http\Error\HttpNotFoundException;
use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Reservation;
use App\Services\VenueService;
use Attribute;

class ReservationService{

    public function __construct(protected VenueService $venueService) {}

    public function list()
    {
        $reservation = Reservation::with('venue')->get();

        return $reservation;
    }

    public function listByVenue(int $id)
    {
        $this->validateVenue($id);

        $reservations = Reservation::where('venue_id', $id)->with('venue')->get();

        return $reservations;
    }

    public function listByCondominium(int $id){
        $reservations = Reservation::whereHas('venue', function ($query) use ($id) {
            $query->where('condominium_id', $id);
        })
        ->with('venue','venue.condominium')
        ->get();

        return $reservations;
    }

    public function find(int $id)
    {
        $reservation = Reservation::with('venue')->find($id);

        if (!$reservation){
            throw new HttpNotFoundException('Reservation not found');
        }

        return $reservation;
    }

    public function create(array $data)
    {
        $this->validateVenue($data['venue_id']);
        $this->validateReservationData($data);

        $reservation = Reservation::create([
            'venue_id' => $data['venue_id'],
            'name' => $data['name'],
            'guest_count' => $data['guest_count'] ?? null,
            'date' => $data['date']
        ]);

        return $reservation;
    }

    public function update(int $id, array $data)
    {
        $reservation = $this->find($id);
        $this->validateVenue($data['venue_id']);
        $this->validateReservationData($data);

        $reservation->fill([
            'venue_id' => $data['venue_id'],
            'name' => $data['name'],
            'guest_count' => $data['guest_count'] ?? null,
            'date' => $data['date']
        ]);

        $reservation->save();

        return $reservation;
    }

    public function delete(int $id): bool
    {
        $reservation = $this->find($id);
        $deleted = $reservation->delete();

        return $deleted;
    }

    private function validateReservationData(array $data){
        if (empty($data['venue_id'])){
            throw new HttpUnprocessableEntityException('venue ID is required');
        }

        if(empty($data['name'])){
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if(isset($data['guest_count']) && !is_null($data['guest_count'])){
            $venue = $this->venueService->find($data['venue_id']);
            $guests = $venue->getAttribute('maximum_occupancy');
            if(!is_numeric($data['guest_count']) || $data['guest_count'] < 0 || $data['guest_count'] != (int)$data['guest_count']){
                throw new HttpUnprocessableEntityException('Guest count must be a non-negative integer');
            }
            if($data['guest_count'] > $guests){
                throw new HttpUnprocessableEntityException('Guest count cannot be bigger than the maximum occupancy of venue');
            }
        }

        if (empty($data['date'])) {
            throw new HttpUnprocessableEntityException('Date is required');
        }

        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s', $data['date']);

        if (!$date) {
            throw new HttpUnprocessableEntityException('Date must be in format YYYY-MM-DD');
        }
    }

    private function validateVenue(int $id):void
    {
        $this->venueService->find($id);
    }

}