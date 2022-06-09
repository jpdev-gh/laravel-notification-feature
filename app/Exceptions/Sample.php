<?php

namespace App\Exceptions;

use Exception;

class Sample extends Exception
{
    public function report()
    {

    }
    
    public function render()
    {
        return response()->json([
            'errors' => 'sample error'
        ]);
    }
}
