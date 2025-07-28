# Company Screener

A comprehensive Laravel application that allows users to screen companies and contractors by analyzing their reviews, license status, and providing a weighted scoring system.

## Features

- **Multi-Platform Review Analysis**: Integrates with Yelp, Google Places, and Facebook APIs
- **AI-Powered Summarization**: Uses OpenAI to analyze reviews and extract top pros and cons
- **License Verification**: Checks business license status using AI
- **Weighted Scoring System**: Calculates scores based on reviews, license status, and review volume
- **Modern UI**: Built with Livewire and Tailwind CSS for a responsive, interactive experience

## Requirements

- PHP 8.1+
- Laravel 12.x
- Node.js 20+
- Composer
- SQLite (or MySQL/PostgreSQL)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd screener
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Set up environment variables**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure API Keys**
   Add the following API keys to your `.env` file:
   ```
   YELP_API_KEY=your_yelp_api_key_here
   GOOGLE_PLACES_API_KEY=your_google_places_api_key_here
   OPENAI_API_KEY=your_openai_api_key_here
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## API Keys Required

### Yelp API
- Sign up at [Yelp Developer](https://www.yelp.com/developers)
- Create a new app to get your API key
- Used for business search and review retrieval

### Google Places API
- Go to [Google Cloud Console](https://console.cloud.google.com/)
- Enable the Places API
- Create credentials (API key)
- Used for Google business reviews and information

### OpenAI API
- Sign up at [OpenAI](https://platform.openai.com/)
- Generate an API key
- Used for review summarization and license verification

## Usage

1. Navigate to the application in your browser
2. Enter a company name and location (city, state optional)
3. The system will search for matching businesses
4. If multiple businesses are found, select the correct one
5. View the comprehensive analysis including:
   - Overall score (0-100)
   - Review score breakdown
   - License status and score
   - Volume score based on number of reviews
   - Top 5 pros and cons from reviews
   - Recent reviews from multiple platforms

## Scoring System

The application uses a weighted scoring system:

- **Review Score (50%)**: Based on average rating and number of reviews
- **License Score (30%)**: Based on license status and verification
- **Volume Score (20%)**: Based on total number of reviews (250+ reviews = maximum score)

## Database Structure

### Companies Table
- Basic company information
- API IDs for different platforms
- Cached review summaries and scores

### Reviews Table
- Individual reviews from different platforms
- Rating, content, and metadata

### Company Scores Table
- Historical scoring data
- Detailed breakdown of scoring factors

## Development

### Adding New Review Platforms

1. Create a new service class in `app/Services/`
2. Implement the required methods for business search and review retrieval
3. Update the `CompanyScreeningService` to include the new platform
4. Add the platform to the review saving logic

### Customizing Scoring Weights

Modify the `ScoringService` class to adjust the scoring algorithm:
- Change weight percentages in `calculateScore()`
- Modify individual score calculations
- Add new scoring factors

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
