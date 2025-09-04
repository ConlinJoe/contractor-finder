<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CSLBService
{
    public function searchLicense(string $companyName, string $city, string $state = null): ?License
    {
        // Only search for California licenses for now
        if ($state && strtoupper($state) !== 'CA' && strtoupper($state) !== 'CALIFORNIA') {
            return null;
        }

        // Clean and normalize the company name for searching
        $normalizedName = $this->normalizeCompanyName($companyName);

        // Search for exact matches first
        $license = $this->searchExactMatch($normalizedName, $city);
        if ($license) {
            return $license;
        }

        // Search for partial matches
        $license = $this->searchPartialMatch($normalizedName, $city);
        if ($license) {
            return $license;
        }

        // Search by city only if no matches found
        return $this->searchByCity($normalizedName, $city);
    }

    private function normalizeCompanyName(string $name): string
    {
        // Remove common business suffixes and normalize
        $normalized = strtoupper(trim($name));
        $normalized = preg_replace('/\b(INC|LLC|CORP|CORPORATION|LTD|LIMITED|CO|COMPANY|&|AND)\b/i', '', $normalized);
        $normalized = preg_replace('/[^\w\s]/', '', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        return trim($normalized);
    }

    private function searchExactMatch(string $normalizedName, string $city): ?License
    {
        return License::where('city', 'LIKE', '%' . strtoupper($city) . '%')
            ->where(function ($query) use ($normalizedName) {
                $query->where('business_name', 'LIKE', '%' . $normalizedName . '%')
                      ->orWhere('full_business_name', 'LIKE', '%' . $normalizedName . '%');
            })
            ->where('primary_status', 'CLEAR')
            ->first();
    }

    private function searchPartialMatch(string $normalizedName, string $city): ?License
    {
        $words = explode(' ', $normalizedName);
        if (count($words) < 2) {
            return null;
        }

        return License::where('city', 'LIKE', '%' . strtoupper($city) . '%')
            ->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    if (strlen($word) > 2) { // Only search for words longer than 2 characters
                        $query->where(function ($subQuery) use ($word) {
                            $subQuery->where('business_name', 'LIKE', '%' . $word . '%')
                                    ->orWhere('full_business_name', 'LIKE', '%' . $word . '%');
                        });
                    }
                }
            })
            ->where('primary_status', 'CLEAR')
            ->first();
    }

    private function searchByCity(string $normalizedName, string $city): ?License
    {
        return License::where('city', 'LIKE', '%' . strtoupper($city) . '%')
            ->where(function ($query) use ($normalizedName) {
                $query->where('business_name', 'LIKE', '%' . $normalizedName . '%')
                      ->orWhere('full_business_name', 'LIKE', '%' . $normalizedName . '%');
            })
            ->where('primary_status', 'CLEAR')
            ->first();
    }

    public function importCSVData(string $csvPath): int
    {
        $imported = 0;
        $handle = fopen($csvPath, 'r');

        if (!$handle) {
            Log::error('Could not open CSV file: ' . $csvPath);
            return 0;
        }

        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return 0;
        }

        // Clear existing data
        License::truncate();

        // Process each row
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($headers)) {
                continue; // Skip malformed rows
            }

            $data = array_combine($headers, $row);

            try {
                License::create([
                    'license_no' => $data['LicenseNo'] ?? null,
                    'last_update' => $this->parseDate($data['LastUpdate'] ?? null),
                    'business_name' => $data['BusinessName'] ?? null,
                    'bus_name_2' => $data['BUS-NAME-2'] ?? null,
                    'full_business_name' => $data['FullBusinessName'] ?? null,
                    'mailing_address' => $data['MailingAddress'] ?? null,
                    'city' => $data['City'] ?? null,
                    'state' => $data['State'] ?? null,
                    'county' => $data['County'] ?? null,
                    'zip_code' => $data['ZIPCode'] ?? null,
                    'country' => $data['country'] ?? null,
                    'business_phone' => $data['BusinessPhone'] ?? null,
                    'business_type' => $data['BusinessType'] ?? null,
                    'issue_date' => $this->parseDate($data['IssueDate'] ?? null),
                    'reissue_date' => $this->parseDate($data['ReissueDate'] ?? null),
                    'expiration_date' => $this->parseDate($data['ExpirationDate'] ?? null),
                    'inactivation_date' => $this->parseDate($data['InactivationDate'] ?? null),
                    'reactivation_date' => $this->parseDate($data['ReactivationDate'] ?? null),
                    'pending_suspension' => $data['PendingSuspension'] ?? null,
                    'pending_class_removal' => $data['PendingClassRemoval'] ?? null,
                    'pending_class_replace' => $data['PendingClassReplace'] ?? null,
                    'primary_status' => $data['PrimaryStatus'] ?? null,
                    'secondary_status' => $data['SecondaryStatus'] ?? null,
                    'classifications' => $data['Classifications(s)'] ?? null,
                    'asbestos_reg' => $data['AsbestosReg'] ?? null,
                    'workers_comp_coverage_type' => $data['WorkersCompCoverageType'] ?? null,
                    'wc_insurance_company' => $data['WCInsuranceCompany'] ?? null,
                    'wc_policy_number' => $data['WCPolicyNumber'] ?? null,
                    'wc_effective_date' => $this->parseDate($data['WCEffectiveDate'] ?? null),
                    'wc_expiration_date' => $this->parseDate($data['WCExpirationDate'] ?? null),
                    'wc_cancellation_date' => $this->parseDate($data['WCCancellationDate'] ?? null),
                    'wc_suspend_date' => $this->parseDate($data['WCSuspendDate'] ?? null),
                    'cb_surety_company' => $data['CBSuretyCompany'] ?? null,
                    'cb_number' => $data['CBNumber'] ?? null,
                    'cb_effective_date' => $this->parseDate($data['CBEffectiveDate'] ?? null),
                    'cb_cancellation_date' => $this->parseDate($data['CBCancellationDate'] ?? null),
                    'cb_amount' => $this->parseDecimal($data['CBAmount'] ?? null),
                    'wb_surety_company' => $data['WBSuretyCompany'] ?? null,
                    'wb_number' => $data['WBNumber'] ?? null,
                    'wb_effective_date' => $this->parseDate($data['WBEffectiveDate'] ?? null),
                    'wb_cancellation_date' => $this->parseDate($data['WBCancellationDate'] ?? null),
                    'wb_amount' => $this->parseDecimal($data['WBAmount'] ?? null),
                    'db_surety_company' => $data['DBSuretyCompany'] ?? null,
                    'db_number' => $data['DBNumber'] ?? null,
                    'db_effective_date' => $this->parseDate($data['DBEffectiveDate'] ?? null),
                    'db_cancellation_date' => $this->parseDate($data['DBCancellationDate'] ?? null),
                    'db_amount' => $this->parseDecimal($data['DBAmount'] ?? null),
                    'date_required' => $this->parseDate($data['DateRequired'] ?? null),
                    'discp_case_region' => $data['DiscpCaseRegion'] ?? null,
                    'db_bond_reason' => $data['DBBondReason'] ?? null,
                    'db_case_no' => $data['DBCaseNo'] ?? null,
                    'name_tp_2' => $data['NAME-TP-2'] ?? null,
                ]);

                $imported++;
            } catch (\Exception $e) {
                Log::error('Error importing license record', [
                    'license_no' => $data['LicenseNo'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }

        fclose($handle);
        Log::info('CSLB data import completed', ['imported_count' => $imported]);

        return $imported;
    }

    private function parseDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Handle MM/DD/YYYY format
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                return \DateTime::createFromFormat('m/d/Y', $date)->format('Y-m-d');
            }

            // Handle other common formats
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
        } catch (\Exception $e) {
            Log::warning('Could not parse date: ' . $date);
        }

        return null;
    }

    private function parseDecimal(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Remove commas and convert to float
        $cleaned = str_replace(',', '', trim($value));
        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}
