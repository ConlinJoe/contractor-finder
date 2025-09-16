<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Free vs Membership</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Start with our free tier to experience the power of AI-driven contractor vetting, then upgrade for unlimited access.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Free Tier -->
            <x-sections.pricing-card
                title="Free Tier"
                price="0"
                period=""
                description="Perfect for trying our service"
                :features="[
                    ['text' => 'AI-powered contractor search', 'included' => true],
                    ['text' => 'Basic contractor profiles', 'included' => true],
                    ['text' => 'Number of searches (1-2 searches)', 'included' => true],
                    ['text' => 'Basic review summaries', 'included' => true],
                    ['text' => 'Detailed vetting reports', 'included' => false],
                    ['text' => 'Direct contact information', 'included' => false],
                    ['text' => 'Priority support', 'included' => false],
                    ['text' => 'Export reports', 'included' => false]
                ]"
                buttonText="Start Free"
                buttonVariant="outline"
                buttonHref="{{ route('search') }}"
            />

            <!-- Membership -->
            <x-sections.pricing-card
                title="Membership"
                price="29"
                period="/month"
                description="Unlimited contractor vetting"
                :features="[
                    ['text' => 'AI-powered contractor search', 'included' => true],
                    ['text' => 'Basic contractor profiles', 'included' => true],
                    ['text' => 'Unlimited searches', 'included' => true],
                    ['text' => 'Advanced review summaries', 'included' => true],
                    ['text' => 'Detailed vetting reports', 'included' => true],
                    ['text' => 'Direct contact information', 'included' => true],
                    ['text' => 'Priority support', 'included' => true],
                    ['text' => 'Export reports', 'included' => true]
                ]"
                buttonText="Get Membership"
                buttonVariant="primary"
                buttonHref="{{ route('search') }}"
                :popular="true"
            />
        </div>
    </div>
</div>
