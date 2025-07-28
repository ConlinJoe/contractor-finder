<?php

namespace App\Livewire;

use App\Services\CompanyScreeningService;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CompanyScreener extends Component
{
    #[Rule('required|min:2')]
    public string $companyName = '';

    #[Rule('required|min:2')]
    public string $city = '';

    public ?string $state = null;

    public bool $isLoading = false;
    public bool $showResults = false;
    public bool $showBusinessSelection = false;
    public array $businesses = [];
    public ?array $selectedBusiness = null;
    public ?array $results = null;
    public string $errorMessage = '';

    public function search()
    {
        $this->validate();
        $this->isLoading = true;
        $this->showResults = false;
        $this->showBusinessSelection = false;
        $this->errorMessage = '';

        try {
            $screeningService = app(CompanyScreeningService::class);
            $result = $screeningService->screenCompany($this->companyName, $this->city, $this->state);

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
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

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while searching for the company.';
        }

        $this->isLoading = false;
    }

    public function selectBusiness(string $yelpId)
    {
        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            $screeningService = app(CompanyScreeningService::class);
            $result = $screeningService->processSelectedBusiness($yelpId, $this->companyName, $this->city, $this->state);

            if (!$result['success']) {
                $this->errorMessage = $result['message'];
                $this->isLoading = false;
                return;
            }

            $this->results = $result;
            $this->showResults = true;
            $this->showBusinessSelection = false;

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while processing the selected business.';
        }

        $this->isLoading = false;
    }

    public function resetSearch()
    {
        $this->reset(['companyName', 'city', 'state', 'isLoading', 'showResults', 'showBusinessSelection', 'businesses', 'results', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.company-screener');
    }
}
