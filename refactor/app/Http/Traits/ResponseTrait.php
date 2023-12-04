<?php

namespace DTApi\Http\Traits;

use Illuminate\Http\JsonResponse;

use \Exception;

trait ResponseTrait
{
    /**
     * @param string $message
     * @param array $data
     * @param array $additional_response
     * @return JsonResponse
     */
    public function successResponse(string $message = '', array $data = [], array $additional_response = []): JsonResponse
    {
        return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ] + $additional_response, 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * @param string $message
     * @param array $additional_response
     * @return JsonResponse
     */
    public function errorResponse(string $message = '', array $additional_response = []): JsonResponse
    {
        return response()->json([
                'success' => false,
                'message' => $message,
            ] + $additional_response, 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function jsonResponse(array $data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * @param Exception $e
     * @return JsonResponse
     */
    private function exceptionToResponse(Exception $e): JsonResponse
    {
        return $this->errorResponse($e->getMessage(), [
            'exception' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ]
        ]);
    }
}