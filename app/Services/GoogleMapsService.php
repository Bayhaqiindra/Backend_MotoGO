<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleMapsService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GOOGLE_MAPS_API_KEY');  // Ambil API Key dari file .env
    }

    /**
     * Mendapatkan koordinat latitude dan longitude berdasarkan alamat
     *
     * @param string $address
     * @return array
     */
    public function geocodeAddress($address)
    {
        try {
            // URL untuk mengakses Google Maps Geocoding API
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $this->apiKey;

            // Melakukan GET request ke Google Maps API
            $response = $this->client->get($url);

            // Mendapatkan data JSON dari response
            $data = json_decode($response->getBody()->getContents(), true);

            // Jika statusnya OK, ambil latitude dan longitude
            if ($data['status'] == 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                ];
            }

            // Jika gagal, return null
            return null;
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            \Log::error('Google Maps API Error: ' . $e->getMessage());
            return null;
        }
    }
}
