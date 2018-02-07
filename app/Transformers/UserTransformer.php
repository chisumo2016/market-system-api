<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{


    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            //
            'identifier' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'creationDate' =>(string) $user->created_at,
            'lastChange' => (string) $user->updated_at,
            'deletedDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null ,


            //HATEOAS
            'links' => [
                    [
                        'rel' => 'self',
                        'href' => route('users.show', $user->id),
                    ],

                ]
        ];


    }

      //Transformation
    public static function  originalAttribute($index)
    {
        $attribute =  [
            //
            'identifier' => 'id',
            'name' =>'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
            'creationDate' =>'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;

    }


    //Improving Transformation  // revert the order of the above array
    public static function  transformedAttribute($index)
    {
        $attribute =  [
            //
            'id' => 'identifier',
            'name' =>'name',
            'email' => 'email',
            'verified' =>'isVerified',
            'admin' => 'isAdmin',
            'created_at' =>'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;

    }
}
