<?php

namespace App\Controllers\Admin;

use App\Core\AdminGuard;
use App\Core\View;
use App\Services\CompanyService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class AdminCompanyController
{
    public function __construct(
        private CompanyService $companyService,
        private AdminGuard $adminGuard
    ) {
    }

    private function basePath(): string
    {
        return rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );
    }

    // Generate or return the current CSRF token
    private function csrfToken(): string
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
    }

    // Check if the submitted CSRF token is valid
    private function checkCsrf(): void
    {
        $token = $_POST["csrf_token"] ?? null;

        if (
            !is_string($token) ||
            !hash_equals($this->csrfToken(), $token)
        ) {
            http_response_code(403);
            echo "403 - Invalid CSRF token.";
            exit;
        }
    }

    // Get company data from the submitted form
    private function postValues(): array
    {
        $values = [];

        foreach ([
            "name",
            "description",
            "email",
            "phone",
            "address"
        ] as $field) {
            $value = $_POST[$field] ?? "";

            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }

            $values[$field] = $value;
        }

        return $values;
    }

    // Render the form with values and optional messages
    private function form(
        array $values,
        ?string $error = null,
        ?string $message = null
    ): void {
        View::render(
            "pages/admin/company/company-form",
            [
                "title" => "Manage Company",
                "values" => $values,
                "error" => $error,
                "message" => $message,
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    // Load company details and display the edit form
    public function edit(): void
    {
        $this->adminGuard->requireAdmin();

        $company = $this->companyService->getCompany();

        if ($company === null) {
            http_response_code(404);
            echo "Company not found.";
            return;
        }

        $message = $_SESSION["admin_company_message"] ?? null;
        unset($_SESSION["admin_company_message"]);

        $this->form(
            [
                "name" => $company->getName(),
                "description" => $company->getDescription() ?? "",
                "email" => $company->getEmail(),
                "phone" => $company->getPhone() ?? "",
                "address" => $company->getAddress() ?? ""
            ],
            null,
            $message
        );
    }

    // Validate and save updated company information
    public function update(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $values = [];

        try {
            $values = $this->postValues();

            $this->companyService->updateCompany($values);

            $_SESSION["admin_company_message"] =
                "Company information updated successfully.";

            header(
                "Location: " . $this->basePath() . "/admin/company",
                true,
                303
            );
            exit;

        } catch (InvalidArgumentException $error) {
            $this->form(
                $values,
                $error->getMessage()
            );

        } catch (DomainException $error) {
            http_response_code(404);
            echo "Company not found.";

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                $values,
                "Could not update company information."
            );
        }
    }
}
