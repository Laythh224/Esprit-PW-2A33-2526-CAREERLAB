<?php

abstract class AbstractSignupController
{
    protected function createInitialState(): array
    {
        return ['serverError' => ''];
    }

    protected function processSubmission(callable $submitOperation, ?callable $onError = null): array
    {
        $state = $this->createInitialState();

        if (!isset($_POST['submit'])) {
            return $state;
        }

        try {
            $submitOperation();
        } catch (Throwable $exception) {
            if ($onError !== null) {
                $onError($exception);
            }

            $state['serverError'] = $exception->getMessage();
        }

        return $state;
    }

    protected function validateCommonSignup(
        SignupValidator $validator,
        AccountRegistry $accountRegistry,
        array $requiredFields,
        string $email
    ): void {
        $validator->assertRequiredFields($requiredFields);
        $validator->assertValidEmail($email);
        $validator->assertUniqueEmail($accountRegistry, $email);
    }

    protected function redirectToLogin(): void
    {
        header('Location: index.php?page=login');
        exit;
    }

    protected function renderSignupView(string $viewFile, array $signupState): void
    {
        $serverError = $signupState['serverError'] ?? '';
        require_once __DIR__ . '/../views/' . $viewFile;
    }
}