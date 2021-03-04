<?php

namespace App\Http\Controllers\Serie;

use App\Serie;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SeriePreguntaController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:serie');
    }
    
    public function index($id)
    {
        $serie = Serie::find($id);
        
        $preguntas = $serie->preguntas()->with('respuestas')->get();
        return $this->showAll($preguntas);
    }
}
