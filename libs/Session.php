<?php
    // Own session class gives me a better idea of what is done with sessions and puts everything I do in one place
class Session
{
    public static function init()
    {
        @session_start();
    }
    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;   
    }
    public static function get(string $key)
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
    }
    public static function exists()
    {
        // Check if you're logged in
        return Session::get('UserID') != null;
    }
    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
}