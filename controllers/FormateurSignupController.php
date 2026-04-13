<?php

class FormateurSignupController extends AbstractSignupController
{
    private FormateurRepositoryInterface $repository;
    private AccountRegistry $accountRegistry;
    private SignupValidator $validator;
    private CvUploadService $cvUploadService;
    private SignupRegistrationService $signupRegistrationService;

    public function __construct(
        FormateurRepositoryInterface $repository,
        AccountRegistry $accountRegistry,
        SignupValidator $validator,
        CvUploadService $cvUploadService,
        SignupRegistrationService $signupRegistrationService
    )
    {
        $this->repository = $repository;
        $this->accountRegistry = $accountRegistry;
        $this->validator = $validator;
        $this->cvUploadService = $cvUploadService;
        $this->signupRegistrationService = $signupRegistrationService;
    }

    public function process(): array
    {
        $storedCvPath = null;

        return $this->processSubmission(
            function () use (&$storedCvPath): void {
                $signupData = FormateurSignupData::fromPost($_POST);
                $email = $signupData->email();

                $this->validateCommonSignup(
                    $this->validator,
                    $this->accountRegistry,
                    $signupData->requiredFields(),
                    $email
                );

                $cvUpload = $this->cvUploadService->uploadPdf($_FILES['cv'] ?? []);
                $storedCvPath = $cvUpload['diskPath'];
                $cv = $cvUpload['publicPath'];

                $this->signupRegistrationService->registerFormateur($this->repository, $email, $signupData->toRepositoryPayload($cv));
                $this->redirectToLogin();
            },
            function () use (&$storedCvPath): void {
                $this->cvUploadService->deleteFile($storedCvPath);
            }
        );
    }

    public function index(): void
    {
        $signupState = $this->process();
        $this->renderSignupView('formateur.view.php', $signupState);
    }
}