<?php

class AppContainer
{
    private PDO $conn;
    private AccountRegistry $accountRegistry;
    private SignupValidator $signupValidator;
    private CvUploadService $cvUploadService;
    private SignupRegistrationService $signupRegistrationService;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->accountRegistry = new AccountRegistry($conn);
        $this->signupValidator = new SignupValidator();
        $this->cvUploadService = new CvUploadService();
        $this->signupRegistrationService = new SignupRegistrationService($conn, $this->accountRegistry);
    }

    public function authRepository(): AuthRepository
    {
        return new AuthRepository($this->conn);
    }

    public function userRepository(): UserRepositoryInterface
    {
        return new UserRepository($this->conn);
    }

    public function formateurRepository(): FormateurRepositoryInterface
    {
        return new FormateurRepository($this->conn);
    }

    public function entrepriseRepository(): EntrepriseRepositoryInterface
    {
        return new EntrepriseRepository($this->conn);
    }

    public function makeAuthController(): AuthController
    {
        return new AuthController($this->authRepository());
    }

    public function makeDashboardController(): DashboardController
    {
        return new DashboardController($this->authRepository());
    }

    public function makeUserSignupController(): UserSignupController
    {
        return new UserSignupController(
            $this->userRepository(),
            $this->accountRegistry,
            $this->signupValidator,
            $this->cvUploadService,
            $this->signupRegistrationService
        );
    }

    public function makeFormateurSignupController(): FormateurSignupController
    {
        return new FormateurSignupController(
            $this->formateurRepository(),
            $this->accountRegistry,
            $this->signupValidator,
            $this->cvUploadService,
            $this->signupRegistrationService
        );
    }

    public function makeEntrepriseSignupController(): EntrepriseSignupController
    {
        return new EntrepriseSignupController(
            $this->entrepriseRepository(),
            $this->accountRegistry,
            $this->signupValidator,
            $this->signupRegistrationService
        );
    }

    public function makeUserApiController(): UserApiController
    {
        return new UserApiController(new UserRepository($this->conn));
    }

    public function makeFormateurApiController(): FormateurApiController
    {
        return new FormateurApiController(new FormateurRepository($this->conn));
    }

    public function makeEntrepriseApiController(): EntrepriseApiController
    {
        return new EntrepriseApiController(new EntrepriseRepository($this->conn));
    }
}