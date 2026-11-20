<?php

declare(strict_types=1);

namespace F9Web;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function response;

trait ApiResponseHelpers
{
    private ?array $_api_helpers_defaultSuccessData = [
      'success' => true,
    ];

    public function respondNotFound(
      string|Throwable $message,
      ?string $key = 'error'
    ): JsonResponse {
        return $this->apiResponse(
          data: [$key => $this->morphMessage($message)],
          code: Response::HTTP_NOT_FOUND
        );
    }

    public function respondWithSuccess(
      array|Arrayable|JsonSerializable|null $contents = null
    ): JsonResponse {
        $contents = $this->morphToArray(data: $contents) ?? [];

        $data = [] === $contents
          ? $this->_api_helpers_defaultSuccessData
          : $contents;

        return $this->apiResponse(data: $data);
    }

    public function setDefaultSuccessResponse(?array $content = null): self
    {
        $this->_api_helpers_defaultSuccessData = $content ?? [];

        return $this;
    }

    public function respondOk(string $message): JsonResponse
    {
        return $this->respondWithSuccess(contents: ['success' => $message]);
    }

    public function respondUnAuthenticated(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
          data: ['error' => $message ?? 'Unauthenticated'],
          code: Response::HTTP_UNAUTHORIZED
        );
    }

    public function respondForbidden(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
          data: ['error' => $message ?? 'Forbidden'],
          code: Response::HTTP_FORBIDDEN
        );
    }

    public function respondError(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
          data: ['error' => $message ?? 'Error'],
          code: Response::HTTP_BAD_REQUEST
        );
    }

    public function respondCreated(
      array|Arrayable|JsonSerializable|null $data = null
    ): JsonResponse {
        return $this->apiResponse(
          data: $this->morphToArray(data: $data) ?? [],
          code: Response::HTTP_CREATED
        );
    }

    public function respondFailedValidation(
      string|Throwable $message,
      ?string $key = 'message'
    ): JsonResponse {
        return $this->apiResponse(
          data: [$key => $this->morphMessage($message)],
          code: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function respondTeapot(): JsonResponse
    {
        return $this->apiResponse(
          data: ['message' => 'I\'m a teapot'],
          code: Response::HTTP_I_AM_A_TEAPOT
        );
    }

    /**
     * Per RFC 9110, a 204 response MUST NOT include content. Passing
     * data to apiResponse() here violates the specification - any
     * provided body is potentially ignored and can cause client
     * errors. Functionality to return data here was added for legacy
     * purposes only. In new projects call the method
     * omitting any arguments
     *
     * @deprecated Will be removed in the next major release. The $data parameter
     *             violates RFC 9110 — a 204 response MUST NOT include content.
     *             Use respondNoContent() with no arguments.
     */
    public function respondNoContent(
      array|Arrayable|JsonSerializable|null $data = null
    ): JsonResponse {
        return $this->apiResponse(
          data: $this->morphToArray(data: $data) ?? [],
          code: Response::HTTP_NO_CONTENT
        );
    }

    public function respondAccepted(
      array|Arrayable|JsonSerializable|null $data = null
    ): JsonResponse {
        return $this->apiResponse(
          data: $this->morphToArray(data: $data) ?? [],
          code: Response::HTTP_ACCEPTED
        );
    }

    public function respondTooManyRequests(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
          data: ['error' => $message ?? 'Too Many Requests'],
          code: Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    public function respondConflict(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
          data: ['error' => $message ?? 'Conflict'],
          code: Response::HTTP_CONFLICT
        );
    }

    private function apiResponse(array $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(data: $data, status: $code);
    }

    private function morphToArray(array|Arrayable|JsonSerializable|null $data): ?array
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            $result = $data->jsonSerialize();
            return is_array($result) ? $result : [$result];
        }

        return $data;
    }

    private function morphMessage(string|Throwable $message): string
    {
        return $message instanceof Throwable
          ? $message->getMessage()
          : $message;
    }
}
