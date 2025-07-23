<?php

namespace App\Rules\Traits;

use App\Rules\SecureCloudUrl;
use Illuminate\Support\Facades\Validator;

trait ValidateCloudDomainTrait
{
    /**
     * Validates the cloud domain and redirects if necessary.
     *
     * @return mixed
     */
    protected function validateDomain(array $data, bool $redirect = false, string $redirectUrl = '')
    {
        // Jika tipe adalah cloud (2), lakukan validasi URL
        if ($data['tipe'] == 2) {
            $secureCloudUrl = new SecureCloudUrl();

            $validator = Validator::make($data, [
                'url' => ['required', 'url', $secureCloudUrl],
            ]);

            if ($validator->fails()) {
                $allowed = implode(', ', $secureCloudUrl->getTrustedDomains());
                $message = "{$validator->errors()->first()} <br>Domain yang diperbolehkan: {$allowed}";

                redirect_with('error', $message, $redirectUrl, true);
            }

            if ($redirect) {
                // Jika valid, redirect ke URL cloud storage
                return redirect($data['url']);
            }
        }
    }
}