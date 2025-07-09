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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

use Closure as BaseClosure;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

trait ProvidesConvenienceMethods
{
    /**
     * The response builder callback.
     *
     * @var BaseClosure
     */
    protected static $responseBuilder;

    /**
     * The error formatter callback.
     *
     * @var BaseClosure
     */
    protected static $errorFormatter;

    /**
     * Set the response builder callback.
     *
     * @return void
     */
    public static function buildResponseUsing(BaseClosure $callback)
    {
        static::$responseBuilder = $callback;
    }

    /**
     * Set the error formatter callback.
     *
     * @return void
     */
    public static function formatErrorsUsing(BaseClosure $callback)
    {
        static::$errorFormatter = $callback;
    }

    /**
     * Validate the given request with the given rules.
     *
     * @throws ValidationException
     *
     * @return array
     */
    public function validated(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            return $this->throwValidationException($request, $validator);
        }

        return $this->extractInputFromRules($request, $rules);
    }

    /**
     * Get the request input based on the given validation rules.
     *
     * @return array
     */
    protected function extractInputFromRules(Request $request, array $rules)
    {
        return $request->only(collect($rules)->keys()->map(static fn ($rule) => Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule)->unique()->toArray());
    }

    /**
     * Throw the failed validation exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws ValidationException
     *
     * @return void
     */
    protected function throwValidationException(Request $request, $validator)
    {
        try {
            throw new ValidationException(
                $validator,
                $this->buildFailedValidationResponse(
                    $request,
                    $this->formatValidationErrors($validator)
                )
            );
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return $this->invalidJson($request, $e);
            }

            return $this->invalid($request, $e);
        }
    }

    /**
     * Convert a validation exception into a response.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|JsonResponse
     */
    protected function invalid($request, ValidationException $exception)
    {
        $this->withInput();
        $this->withErrors($exception->errors(), $request->input('_error_bag', $exception->errorBag));

        return redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Flash an array of input to the session.
     *
     * @return $this
     */
    protected function withInput(?array $input = null)
    {
        $this->session->set_flashdata('_old_input', $this->removeFilesFromInput(
            null !== $input ? $input : app('request')->input()
        ));
    }

    /**
     * Remove all uploaded files form the given input array.
     *
     * @return array
     */
    protected function removeFilesFromInput(array $input)
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = $this->removeFilesFromInput($value);
            }

            if ($value instanceof SymfonyUploadedFile) {
                unset($input[$key]);
            }
        }

        return $input;
    }

    /**
     * Flash a container of errors to the session.
     *
     * @param array|MessageProvider|string $provider
     * @param string                       $key
     *
     * @return $this
     */
    protected function withErrors($provider, $key = 'default')
    {
        $value = $this->parseErrors($provider);

        $errors = $this->session->errors ?: new ViewErrorBag();

        if (! $errors instanceof ViewErrorBag) {
            $errors = new ViewErrorBag();
        }

        $this->session->set_flashdata('errors', $errors->put($key, $value));
    }

    /**
     * Parse the given errors into an appropriate value.
     *
     * @param array|MessageProvider|string $provider
     *
     * @return MessageBag
     */
    protected function parseErrors($provider)
    {
        if ($provider instanceof MessageProvider) {
            return $provider->getMessageBag();
        }

        return new MessageBag((array) $provider);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return json([
            'message' => $exception->getMessage(),
            'errors'  => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Build a response based on the given errors.
     *
     * @return JsonResponse|mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return (static::$responseBuilder)($request, $errors);
        }

        return new JsonResponse($errors, 422);
    }

    /**
     * Format validation errors.
     *
     * @return array|mixed
     */
    protected function formatValidationErrors(Validator $validator)
    {
        if (isset(static::$errorFormatter)) {
            return (static::$errorFormatter)($validator);
        }

        return $validator->errors()->getMessages();
    }

    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     *
     * @return mixed
     */
    public function dispatch($job)
    {
        return app(Dispatcher::class)->dispatch($job);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $job
     * @param mixed $handler
     *
     * @return mixed
     */
    public function dispatchNow($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchNow($job, $handler);
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app('validator');
    }
}
