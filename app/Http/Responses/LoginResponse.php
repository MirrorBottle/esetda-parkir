<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
      if(auth()->user()->hasRole('admin')){
        return redirect()->intended(route('admin.dashboard'));
      }
      return redirect()->intended(route('logged-in'));
    }
}
