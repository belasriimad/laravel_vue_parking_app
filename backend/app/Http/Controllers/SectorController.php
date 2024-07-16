<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Resources\SectorResource;

class SectorController extends Controller
{
    //
    public function index() 
    {
        return SectorResource::collection(Sector::all());
    }
}
