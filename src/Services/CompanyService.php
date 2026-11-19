<?php

namespace App\Services;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use DomainException;
use InvalidArgumentException;

class CompanyService
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function getCompany(): ?Company
    {
        return $this->companyRepository->findFirst();
    }

    public function updateCompany(array $data): void
    {
        $company = $this->getCompany();

        if ($company === null) {
            throw new DomainException("Company not found.");
        }

        $validated = $this->validateCompany($data);

        $this->companyRepository->update(
            $company->getCompanyID(),
            $validated
        );
    }

    private function validateCompany(array $data): array
    {
        // Check form fields
        foreach ([
            "name",
            "description",
            "email",
            "phone",
            "address"
        ] as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field])
            ) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }
        }

        $name = trim($data["name"]);
        $description = trim($data["description"]);
        $email = trim($data["email"]);
        $phone = trim($data["phone"]);
        $address = trim($data["address"]);

        // Validate company name and description
        if ($name === "" || strlen($name) > 150) {
            throw new InvalidArgumentException(
                "Company name is required (max 150 bytes)."
            );
        }

        if (strlen($description) > 65535) {
            throw new InvalidArgumentException(
                "Company description is too long."
            );
        }

        // Validate contact information
        if (
            strlen($email) > 190 ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            throw new InvalidArgumentException(
                "Enter a valid email address."
            );
        }

        if (strlen($phone) > 30) {
            throw new InvalidArgumentException(
                "Phone number is too long."
            );
        }

        if (strlen($address) > 255) {
            throw new InvalidArgumentException(
                "Address is too long."
            );
        }

        // Return validated data for the repository
        return [
            "name" => $name,
            "description" => $description !== ""
                ? $description : null,
            "email" => $email,
            "phone" => $phone !== "" ? $phone : null,
            "address" => $address !== "" ? $address : null
        ];
    }
}
