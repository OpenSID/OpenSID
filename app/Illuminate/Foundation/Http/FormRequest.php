<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Illuminate\Foundation\Http;

use App\Traits\ProvidesConvenienceMethods;
use Illuminate\Support\Facades\Validator;
use Throwable;

abstract class FormRequest
{
    use ProvidesConvenienceMethods;

    /**
     * The session instance.
     *
     * @var mixed
     */
    protected $session;

    /**
     * The validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * The data instance.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The error messages instance.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    public function __construct()
    {
        $this->data = $this->all();
        $this->validate();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all()
    {
        if (! empty($this->data)) {
            return $this->data;
        }

        return array_merge(request()->all(), request()->allFiles());
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRulesWithAttributeNames()
    {
        return $this->rules();
    }

    /**
     * Get the validated data from the request.
     *
     * @param array|null $keys
     *
     * @return array
     */
    public function validated($keys = null)
    {
        $validated = $this->validator->validated();

        if (null === $keys) {
            return $validated;
        }

        return array_intersect_key($validated, array_flip((array) $keys));
    }

    /**
     * Get the validator instance.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get all of the input.
     *
     * @param array|string|null $keys
     *
     * @return array
     */
    public function only($keys)
    {
        return array_intersect_key($this->all(), array_flip((array) $keys));
    }

    /**
     * Get all input except the keys.
     *
     * @param array|string|null $keys
     *
     * @return array
     */
    public function except($keys)
    {
        return array_diff_key($this->all(), array_flip((array) $keys));
    }

    /**
     * Validate the request.
     *
     * @return void
     */
    protected function validate()
    {
        if (! $this->authorize()) {
            abort(403, 'Unauthorized');
        }

        $this->prepareForValidation();

        $this->validator = Validator::make(
            $this->all(),
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        if ($this->validator->fails()) {
            $this->failedValidation();
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {

    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     */
    protected function failedValidation()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize session object
        $this->session = $this->getSessionProvider();

        // Format validation errors using custom formatter if available
        $errors = $this->formatValidationErrors($this->validator);

        // Check if this is an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                  && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // For AJAX requests, build JSON response
        if ($isAjax) {
            http_response_code(422);
            header('Content-Type: application/json');

            // Format response dengan struktur { errors: {...} }
            echo json_encode([
                'errors' => $errors,
            ]);

            exit;
        }

        // For regular form submissions: Store errors and old input in session flash data
        $this->withErrors($errors, 'default');
        $this->withInput($this->all());

        // Get previous URL for redirect back to form
        $previousUrl = $_SERVER['HTTP_REFERER'] ?? url('/');

        header('Location: ' . $previousUrl);

        exit;
    }

    /**
     * Get the session provider.
     *
     * @return mixed
     */
    protected function getSessionProvider()
    {
        try {
            $ci = app('ci');
            if ($ci && isset($ci->session)) {
                return $ci->session;
            }
        } catch (Throwable $e) {
            // Fallback to session helper
        }

        // Create a simple session wrapper for compatibility
        return new class () {
            public function set_flashdata($key, $value)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                // Store in session for immediate access and next request
                $_SESSION[$key] = $value;

                // Also mark for flash (auto-delete after next page load)
                if (! isset($_SESSION['__flash_keys'])) {
                    $_SESSION['__flash_keys'] = [];
                }
                $_SESSION['__flash_keys'][$key] = true;
            }

            public function __get($property)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                return $_SESSION[$property] ?? null;
            }

            public function __set($property, $value)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION[$property] = $value;
            }

            public function mark_as_flash($key)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (! isset($_SESSION['__flash_keys'])) {
                    $_SESSION['__flash_keys'] = [];
                }
                $_SESSION['__flash_keys'][$key] = true;
            }

            public function set_userdata($key, $value)
            {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION[$key] = $value;
            }
        };
    }
}
