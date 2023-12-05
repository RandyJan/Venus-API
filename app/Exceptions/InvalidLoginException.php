<?php

namespace App\Exceptions;

use App\Traits\Response;
use Exception;

class InvalidLoginException extends Exception
{
    use Response;

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        //
        // if($request->ajax()){
            return $this->response(
                $this->message,
                0
            );
        // }
    }
}
