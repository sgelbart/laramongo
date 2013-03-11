<?php

class User extends BaseModel implements Illuminate\Auth\UserInterface
{

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'users';

    public static function logAttempt( $credentials )
    {
        $user = User::first([
            '$or'=> [
                ['email'    => $credentials['email'] ] ,
                ['username' => $credentials['email'] ]
            ] 
        ]);

        if ( $user and Hash::check($credentials['password'], $user->password) )
        {
            Auth::login( 
                $user,
                isset($credentials['remember']) ? $credentials['remember'] : false 
            );
            
            return true;
        }
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
