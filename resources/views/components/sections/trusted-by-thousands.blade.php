<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Trusted by Thousands</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Join homeowners who've found reliable contractors through our AI-powered platform.
            </p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
            <x-sections.stat-card
                icon="fas fa-users"
                number="25,000+"
                label="Contractors Vetted"
            />
            <x-sections.stat-card
                icon="fas fa-star"
                number="4.8/5"
                label="Average Rating"
            />
            <x-sections.stat-card
                icon="fas fa-shield-alt"
                number="96%"
                label="Success Rate"
            />
            <x-sections.stat-card
                icon="fas fa-tools"
                number="50,000+"
                label="Projects Completed"
            />
        </div>

        <!-- Testimonials -->
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">What Our Users Say</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-sections.testimonial-card
                    name="Sarah Johnson"
                    role="Homeowner"
                    :rating="5"
                    quote="WeSpeak Verify saved me from hiring a contractor with multiple complaints. The AI analysis was spot-on and helped me make the right choice."
                />
                <x-sections.testimonial-card
                    name="Mike Chen"
                    role="Property Manager"
                    :rating="5"
                    quote="The platform is incredibly fast and accurate. I can verify multiple contractors in minutes instead of hours of research."
                />
                <x-sections.testimonial-card
                    name="Lisa Rodriguez"
                    role="Homeowner"
                    :rating="5"
                    quote="Finally, a tool that actually works! The detailed reports and license verification gave me confidence in my contractor choice."
                />
            </div>
        </div>

        <!-- CTA Button -->
        <div class="text-center">
            <x-ui.button variant="primary" size="xl" href="{{ route('search') }}">
                <i class="fas fa-robot mr-2"></i>
                AI-Powered Vetting Technology
            </x-ui.button>
        </div>
    </div>
</div>
