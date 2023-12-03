Project Setup Instructions:

Thank you for considering my project. To ensure that the project runs smoothly, please follow these prerequisites and setup instructions:

1. **Database Setup:**

   - Create a database with any name.

2. **Run Migrations:**

   - Execute the following command to set up the database tables:

     ```
     php artisan migrate
     ```

3. **Database Seeders:**

   - I've implemented a category seeder to provide some generic categories, as the news API response doesn't specify them. To seed the database with these categories, run:

     ```
     php artisan db:seed
     ```

   - These categories are based on the general categories mentioned in the NewsAPI documentation.

4. **Cron Jobs:**

   - To keep the data up-to-date, there are scheduled cron jobs. These jobs run daily at 12:00 am. To manually run them, execute the following commands:

     ```
     php artisan command:newsapi-import
     php artisan command:nyt-import
     php artisan command:guardian-import
     ```

5. **API Endpoint:**

   - There is an API endpoint available to fetch articles. It's located at `/articles`.

   - Ensure that the request headers include:

     - `Content-Type: application/json`
     - `Accept: application/json`

6. **Filters and User Preferences:**

   - The `/articles` endpoint supports various filters and user preferences:

     - `q`:(string) Search articles by title and description.
     - `date`:(2023-12-01) Retrieve articles based on the publish date.
     - `source`:(string) Search articles by source name.
     - `category`:(id: numeric) To filter by category, provide the `category_id` (frontend can choose this from a dropdown or list).
     - `preferred_sources[]`:(numeric array) You can include multiple source IDs to fetch articles from specific sources.
     - `preferred_authors[]`:(strings in array) Specify an array of author names to filter articles by author.
     - `preferred_categories[]`:(numeric array) Provide an array of category IDs to filter articles by category.

By following these setup instructions, you'll have the project ready to explore and test. If you have any further questions or need assistance, please feel free to ask.

Best regards,
Sufyan Ejaz
