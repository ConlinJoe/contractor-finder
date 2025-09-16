<?php

namespace App\Livewire;

use App\Services\CompanyScreeningService;
use App\Services\JobTypeService;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CompanyScreener extends Component
{
    // Search mode: 'company' or 'job_type'
    public string $searchMode = 'company';

    // Company search fields
    #[Rule('required|min:2')]
    public string $companyName = '';

    #[Rule('required|min:2')]
    public string $city = '';

    public ?string $state = null;

    // Job type search fields
    #[Rule('required')]
    public ?int $selectedJobTypeId = null;

    #[Rule('required|min:2')]
    public string $jobTypeCity = '';

    public ?string $jobTypeState = null;

    public int $jobTypeRadius = 10; // Default to 10 miles

    // Common fields
    public bool $isLoading = false;
    public bool $showResults = false;
    public bool $showBusinessSelection = false;
    public array $businesses = [];
    public ?array $selectedBusiness = null;
    public ?array $results = null;
    public string $errorMessage = '';
    public array $apiIssues = [];
    public array $jobTypes = [];
    public array $jobTypeResults = [];

    public function mount()
    {
        // Check API status on component load
        $screeningService = app(CompanyScreeningService::class);
        $apiStatus = $screeningService->checkApiStatus();

        foreach ($apiStatus as $service => $status) {
            if ($status['status'] !== 'working') {
                $this->apiIssues[] = $status['message'];
            }
        }

        // Load job types
        $jobTypeService = app(JobTypeService::class);
        $this->jobTypes = $jobTypeService->getJobTypes();
    }

    public function search()
    {
        if ($this->searchMode === 'company') {
            $this->searchByCompany();
        } else {
            $this->searchByJobType();
        }
    }

    public function searchByCompany()
    {
        $this->validate([
            'companyName' => 'required|min:2',
            'city' => 'required|min:2',
        ]);

        $this->isLoading = true;
        $this->showResults = false;
        $this->showBusinessSelection = false;
        $this->errorMessage = '';
        $this->apiIssues = [];

        try {
            $screeningService = app(CompanyScreeningService::class);
            $result = $screeningService->screenCompany($this->companyName, $this->city, $this->state);

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
                $this->apiIssues = $result['api_issues'] ?? [];
                $this->isLoading = false;
                return;
            }

            if ($result['multiple_found'] ?? false) {
                $this->businesses = $result['companies'];
                $this->showBusinessSelection = true;
            } else {
                $this->results = $result;
                $this->showResults = true;
            }

            $this->apiIssues = $result['api_issues'] ?? [];

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while searching for the company.';
        }

        $this->isLoading = false;
    }

    public function searchByJobType()
    {
        $this->validate([
            'selectedJobTypeId' => 'required',
            'jobTypeCity' => 'required|min:2',
        ]);

        // Increase execution time limit for job type search with AI processing
        set_time_limit(120);

        $this->isLoading = true;
        $this->showResults = false;
        $this->showBusinessSelection = false;
        $this->errorMessage = '';
        $this->apiIssues = [];
        $this->jobTypeResults = [];

        try {
            $jobTypeService = app(JobTypeService::class);
            $result = $jobTypeService->findContractorsByJobType(
                $this->selectedJobTypeId,
                $this->jobTypeCity,
                $this->jobTypeState,
                $this->jobTypeRadius
            );

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
                $this->apiIssues = $result['api_issues'] ?? [];
                $this->isLoading = false;
                return;
            }

            $this->jobTypeResults = $result;
            $this->showResults = true;
            $this->apiIssues = $result['api_issues'] ?? [];

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while searching for contractors.';
        }

        $this->isLoading = false;
    }

    public function selectBusiness(string $yelpId)
    {
        $this->isLoading = true;
        $this->errorMessage = '';
        $this->apiIssues = [];

        try {
            $screeningService = app(CompanyScreeningService::class);
            $result = $screeningService->processSelectedBusiness($yelpId, $this->companyName, $this->city, $this->state);

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
                $this->apiIssues = $result['api_issues'] ?? [];
                $this->isLoading = false;
                return;
            }

            $this->results = $result;
            $this->showResults = true;
            $this->showBusinessSelection = false;
            $this->apiIssues = $result['api_issues'] ?? [];

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while processing the selected business.';
        }

        $this->isLoading = false;
    }

    public function switchToCompanySearch()
    {
        $this->searchMode = 'company';
        $this->resetSearch();
    }

    public function switchToJobTypeSearch()
    {
        $this->searchMode = 'job_type';
        $this->resetSearch();
    }

    public function resetSearch()
    {
        $this->reset([
            'companyName', 'city', 'state',
            'selectedJobTypeId', 'jobTypeCity', 'jobTypeState', 'jobTypeRadius',
            'isLoading', 'showResults', 'showBusinessSelection',
            'businesses', 'results', 'errorMessage', 'apiIssues', 'jobTypeResults'
        ]);
    }

    public function searchCompany(string $companyName, string $city, ?string $state = null)
    {
        // Switch to company search mode
        $this->searchMode = 'company';

        // Pre-fill the company search form
        $this->companyName = $companyName;
        $this->city = $city;
        $this->state = $state;

        // Reset other fields
        $this->showResults = false;
        $this->showBusinessSelection = false;
        $this->errorMessage = '';
        $this->results = null;
        $this->businesses = [];
        $this->jobTypeResults = [];

        // Process the specific company directly instead of doing a new search
        $this->processSpecificCompany($companyName, $city, $state);
    }

    private function processSpecificCompany(string $companyName, string $city, ?string $state = null)
    {
        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            // Find the existing company in the database
            // Prefer companies with API IDs (more complete data)
            $company = \App\Models\Company::where('name', $companyName)
                ->where('city', $city)
                ->orderByRaw('CASE WHEN yelp_id IS NOT NULL OR google_place_id IS NOT NULL THEN 0 ELSE 1 END')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$company) {
                $this->errorMessage = 'Company not found in database. Please try a new search.';
                $this->isLoading = false;
                return;
            }

            // Process the existing company with full analysis
            $screeningService = app(CompanyScreeningService::class);
            $result = $screeningService->processExistingCompany($company);

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
                $this->apiIssues = $result['api_issues'] ?? [];
                $this->isLoading = false;
                return;
            }

            // Show the detailed results
            $this->results = $result;
            $this->showResults = true;
            $this->apiIssues = $result['api_issues'] ?? [];

            $this->isLoading = false;

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while processing the company: ' . $e->getMessage();
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.company-screener');
    }
}
