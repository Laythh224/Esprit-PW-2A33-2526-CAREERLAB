<?php

class EntrepriseSignupController extends AbstractSignupController
{
    private EntrepriseRepositoryInterface $repository;
    private AccountRegistry $accountRegistry;
    private SignupValidator $validator;
    private SignupRegistrationService $signupRegistrationService;

    public function __construct(
        EntrepriseRepositoryInterface $repository,
        AccountRegistry $accountRegistry,
        SignupValidator $validator,
        SignupRegistrationService $signupRegistrationService
    )
    {
        $this->repository = $repository;
        $this->accountRegistry = $accountRegistry;
        $this->validator = $validator;
        $this->signupRegistrationService = $signupRegistrationService;
    }

    public function process(): array
    {
        return $this->processSubmission(function (): void {
            $signupData = EntrepriseSignupData::fromPost($_POST);
            $email = $signupData->email();

            $this->validateCommonSignup(
                $this->validator,
                $this->accountRegistry,
                $signupData->requiredFields(),
                $email
            );
            $this->validator->assertValidOptionalUrl($signupData->siteWeb());

            $this->signupRegistrationService->registerEntreprise($this->repository, $email, $signupData->toRepositoryPayload());
            $this->redirectToLogin();
        });
    }

    public function index(): void
    {
        $signupState = $this->process();
        $this->renderSignupView('entreprise.view.php', $signupState);
    }
}