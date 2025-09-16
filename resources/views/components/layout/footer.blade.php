<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="md:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">WeSpeak Verify</span>
                </div>
                <p class="text-gray-300 text-sm leading-relaxed">
                    AI-powered contractor vetting platform helping homeowners find reliable professionals for their projects.
                </p>
            </div>

            <!-- Product Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Product</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('how-it-works') }}" class="text-gray-300 hover:text-white transition duration-200">How It Works</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-gray-300 hover:text-white transition duration-200">Pricing</a></li>
                    <li><a href="{{ route('search') }}" class="text-gray-300 hover:text-white transition duration-200">Try Free Search</a></li>
                </ul>
            </div>

            <!-- Company Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Company</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition duration-200">About</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition duration-200">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white transition duration-200">FAQs</a></li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('privacy-policy') }}" class="text-gray-300 hover:text-white transition duration-200">Privacy Policy</a></li>
                    <li><a href="{{ route('terms-of-service') }}" class="text-gray-300 hover:text-white transition duration-200">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © 2024 WeSpeak Verify. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
