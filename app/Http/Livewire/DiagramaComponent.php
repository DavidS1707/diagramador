<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DiagramaSecuencia;

class DiagramaComponent extends Component
{
    public $diagrama;

    public function mount(DiagramaSecuencia $diagrama)
    {
        $this->diagrama = $diagrama;
    }

    public function render()
    {
        return view('livewire.diagrama-component', ['diagrama' => $this->diagrama]);
    }
}
