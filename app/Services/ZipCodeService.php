<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZipCodeService
{
    private $URL;

    public function __construct(String $zip_code_provider_url)
    {
      $this->URL = $zip_code_provider_url;
    }

    public function handle(String $zip_code)
    {
      $response = Http::get($this->URL . $zip_code);

      /** Somente para incrementar o tempo de resposta da API externa e facilitar a visualização do cache */
      sleep(15);

      if (!$response->successful()) {
        Log::warning("Unexpected error was occurred while trying to retrieve 'zip_code' data from external api");
        return false;
      }

      $data = $response->json();

      return [
        'state' => $data['state'],
        'city' => $data['city'],
        'district' => $data['neighborhood'],
        'street' => $data['street'],
      ];
    }
}
