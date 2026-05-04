<?php

class PhoneNumberValidator
{
    public function validate(string $phone): array
    {
        $normalisedPhone = trim($phone);

        if ($normalisedPhone === '') {
            return [
                'isValid' => false,
                'number' => '',
                'error' => 'Le telephone est obligatoire.',
            ];
        }

        if (class_exists(\libphonenumber\PhoneNumberUtil::class)) {
            return $this->validateWithLibphonenumber($normalisedPhone);
        }

        return $this->validateWithFallback($normalisedPhone);
    }

    private function validateWithLibphonenumber(string $phone): array
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneUtil->parse($phone, null);
        } catch (\libphonenumber\NumberParseException $exception) {
            return [
                'isValid' => false,
                'number' => '',
                'error' => 'Format incorrect.',
            ];
        }

        if (!$phoneUtil->isValidNumber($phoneNumber)) {
            return [
                'isValid' => false,
                'number' => '',
                'error' => 'Numero invalide pour ce pays.',
            ];
        }

        return [
            'isValid' => true,
            'number' => $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164),
            'error' => '',
        ];
    }

    private function validateWithFallback(string $phone): array
    {
        $sanitised = preg_replace('/[^\d+]/', '', $phone) ?? '';
        $sanitised = preg_replace('/(?!^)\+/', '', $sanitised) ?? '';

        if (!preg_match('/^\+[1-9]\d{6,14}$/', $sanitised)) {
            return [
                'isValid' => false,
                'number' => '',
                'error' => 'Numéro invalide pour ce pays.',
            ];
        }

        return [
            'isValid' => true,
            'number' => $sanitised,
            'error' => '',
        ];
    }
}

