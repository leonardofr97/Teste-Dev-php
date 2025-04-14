<?php

namespace App\Providers;

use App\Services\ZipCodeService;
use Illuminate\Support\ServiceProvider;

class ZipCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ZipCodeService::class, function ($app) {
            /** Oportunidade de melhoria: carregar a API diretamente do .env */
            return new ZipCodeService("https://brasilapi.com.br/api/cep/v2/");
        });
    }
}
