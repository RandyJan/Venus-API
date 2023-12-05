<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response as SResponse;

trait Response {

    /**
     * @param String $description
     * @param Int $status
     * @param Mix $data
     * @return Response
     */
    public function response($description, $status = 1, $data = null){
        return response()->json([
            'statusCode' => $status,
            'statusDescription' => $description,
            'data' => $data
        ]);
    }
}
