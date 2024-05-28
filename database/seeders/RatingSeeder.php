<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
{
    public function run()
    {
        $handle = fopen("resources/movies-dataset/ratings_small.csv", "r");
        if ($handle) {
            echo "Inserting data in ratings table: ";

            // Initialize an array to keep track of unique ratings
            $uniqueRatings = [];

            // Initialize a counter to track progress
            $index = 0;

            while (($lineValues = fgetcsv($handle, 0, ",")) !== false) {
                // Skip header row
                if ($index == 0) {
                    $index++;
                    continue;
                }

                $user_id = $lineValues[0];
                $movie_id = $lineValues[1];
                $rating = $lineValues[2];

                // Check if rating already exists for the user and movie combination
                $ratingKey = "$user_id-$movie_id";
                if (isset($uniqueRatings[$ratingKey])) {
                    continue; // Skip duplicate rating
                }

                // Add rating to unique ratings array
                $uniqueRatings[$ratingKey] = true;

                // Check if movie exists in movies table
                if (!DB::table('movies')->where('id', $movie_id)->exists()) {
                    continue; // Skip if movie doesn't exist
                }

                // Insert rating into ratings table
                DB::table('ratings')->insert([
                    'user_id' => $user_id,
                    'movie_id' => $movie_id,
                    'rating' => $rating,
                ]);

                // Print progress bar
                $this->printProgressBar(++$index, 26024289);
            }

            fclose($handle);
        }
    }

    // Function to print progress bar
    private function printProgressBar($current, $total)
    {
        $percentage = round(($current / $total) * 100);
        static $lastPercentage = -1;

        if ($percentage > $lastPercentage) {
            echo "=";
            $lastPercentage = $percentage;
        }

        if ($percentage == 100) {
            echo "> 100% completed.\n";
        }
    }
}
