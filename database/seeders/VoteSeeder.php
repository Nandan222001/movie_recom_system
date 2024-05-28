<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get info from file movies_metadata.csv
        $handle = fopen("resources/movies-dataset/movies_metadata.csv", "r");
        if ($handle) {
            echo "Inserting data in votes table: ";

            // Fetch all existing movie IDs from the database
            $existingMovieIds = DB::table('movies')->pluck('id')->all();

            while (($lineValues = fgetcsv($handle, 0, ",")) !== false) {
                static $index = 0;

                // Skip header row
                if ($index == 0) {
                    $index++;
                    continue;
                }

                // Check if movie ID exists and row has a valid length
                if (empty($lineValues[5]) || sizeof($lineValues) < 20) {
                    $index++;
                    continue;
                }

                // Check if movie ID exists in the movies table
                if (!in_array($lineValues[5], $existingMovieIds)) {
                    $index++;
                    continue;
                }

                $vote_average = $lineValues[22];
                $vote_count = $lineValues[23];

                // Skip iteration if vote average or vote count is not numeric
                if (!is_numeric($vote_average) || !is_numeric($vote_count)) {
                    $index++;
                    continue;
                }

                // Loading bar progress
                $percentage = ($index / 45575) * 100;
                static $actual = 0;

                if ($percentage - $actual >= 10) {
                    echo "=";
                    $actual = $percentage;
                }

                static $completed = false;

                if ($percentage >= 99 && !$completed) {
                    echo "> 100% completed.\n";
                    $completed = true;
                }

                $index++;

                // Add vote to table using prepared statement
                $q_insertVote = "INSERT INTO votes (vote_average, vote_count, movie_id) VALUES (?, ?, ?)";

                DB::statement($q_insertVote, [
                    $vote_average,
                    $vote_count,
                    $lineValues[5], // Movie ID
                ]);
            }
        }
        fclose($handle);
    }
}
