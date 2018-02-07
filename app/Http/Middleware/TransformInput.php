<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {

        $transformedInput = [];
        foreach ($request->request->all() as $input => $value){

            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);

        $response = $next($request);

        //validate
        if(isset($response->exception) && $response->exception instanceof  validationException){

            //Data obtained from response
            $data = $response->getData();

            //Error transformed
            $transformedErrors = [];

            foreach ( $data->error as $field => $error){

                $transformerField = $transformer ::transformedAttribute($field);
                //Build Error
                $transformedErrors[$transformerField]  = str_replace($field, $transformerField, $error);
            }

            //Publish new field array
            $data->error = $transformedErrors;
            $response->setData($data);
        }

        return $response;
    }
}
