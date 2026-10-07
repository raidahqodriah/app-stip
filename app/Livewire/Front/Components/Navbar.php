<?php

namespace App\Livewire\Front\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Navbar extends Component
{
    public function render(): View
    {
        return view('livewire.front.components.navbar');
    }
}
