<?php

namespace App\Services;

class ResponseService
{    
    public function success($data = null)
    {
        return $this->response([
            'success' => true,
            'data' => $data
        ]);
    }


    public function created($data = null)
    {
        return $this->response([
            'success' => true,
            'data' => $data
        ], 201);
    }

    public function deleted()
    {
        return $this->response([
            'success' => true,
            'data' => null
        ], 204);
    }

    public function noContent()
    {
        return $this->deleted();
    }
    
    public function forbidden($data = null)
    {
        return $this->response([
            'success' => false,
            'data' => $data
        ], 403);
    }

    public function notFound($data = null)
    {
        return $this->response([
            'success' => false,
            'data' => $data
        ], 404);
    }
   


    public function error($data = null)
    {
        return $this->response([
            'success' => false,
            'data' => $data
        ], 500);
    }

    public function unauthorized($data = null)
    {
        return $this->response([
            'success' => false,
            'data' => $data
        ], 401);
    }

    public function badRequest($data = null)
    {
        return $this->response([
            'success' => false,
            'data' => $data
        ], 400);
    }

    /**
     * Sends a JSON response with the given data and HTTP status.
     *
     * @param array|null $data The data to be included in the response.
     * @param int $status The HTTP status code for the response. Default is 200.
     * @param array $headers Additional headers to be included in the response.
     * @param int $options JSON encoding options.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response object.
     */

    public function response(
        array|null $data = null, int $status = 200, array $headers = [], int $options = 0
    )
    {
        return response()->json($data, $status, $headers, $options);
    }

}