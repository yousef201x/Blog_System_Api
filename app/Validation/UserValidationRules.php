<?php

namespace App\Validation;

trait UserValidationRules
{
    function registerRules(): array
    {
        return [
            // Yousef Mohamed
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            // yousef@mail.com // yousef_123@mail.com // yousef.m@mail.com
            'email' => 'required|email|max:255|unique:users,email',
            // Password@123 // Str0ng!Pass // MySecur3$Pass
            'password' => 'required|string|min:8|max:64|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&#]/',
        ];
    }

    function loginRules(): array
    {
        return [
            // yousef@mail.com // yousef_123@mail.com // yousef.m@mail.com
            'email' => 'required|email|max:255|exists:users,email',
            // Password@123 // Str0ng!Pass // MySecur3$Pass
            'password' => 'required|string|min:8|max:64|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&#]/',
        ];
    }
}
