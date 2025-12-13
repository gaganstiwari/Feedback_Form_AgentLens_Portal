<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Api
{
    
    protected $api_url = "http://localhost:5000/records";

    public function getAll()
    {
        $response = Http::get($this->api_url);

        return $response->json(); 
    }

    public function findById($id)
    {
        $response = Http::get($this->api_url . "/" . $id);

        return $response->json();
    }
}
