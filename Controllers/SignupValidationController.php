<?php

class SignupValidationController
{
    private SignupValidator $validator;

    public function __construct(SignupValidator $validator)
    {
        $this->validator = $validator;
    }

    public function emailAvailability(): void
    {
        $email = $this->validator->cleanEmail((string) ($_GET['email'] ?? ''));

        if ($email === '') {
            JsonResponse::send(false, ['message' => "L'email est obligatoire."], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            JsonResponse::send(false, ['message' => 'Veuillez saisir une adresse email valide.'], 422);
        }

        $exists = $this->validator->emailExists($email);

        JsonResponse::send(true, [
            'available' => !$exists,
            'message' => $exists ? 'Cet email est deja utilise.' : 'Email disponible.',
        ]);
    }
}
