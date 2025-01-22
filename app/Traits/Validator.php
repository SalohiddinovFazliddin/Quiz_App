<?php

namespace App\Traits;

trait Validator
{
    public function validate(array $data): array
    {
        $updates = file_get_contents('php://input');
        if ($data = json_decode($data, true)) {
            $_REQUEST = array_merge($_REQUEST, $data);
        }
        $requiredKeys = [];

        foreach ($data as $key => $value ) {
            if (array_key_exists($key, $_REQUEST) and !empty($_REQUEST[$key])) {
                continue;
            }
            $requiredKeys[$key] = $key . 'is required';
        }
        if (!empty($requiredKeys)) {
            apiResponse([
                'errors' => $requiredKeys], 400);
        }
        return $_REQUEST;
    }
}