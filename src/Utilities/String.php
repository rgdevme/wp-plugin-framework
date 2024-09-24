<?php

namespace WordpressPluginFramework\Utilities;

class StringUtility
{
  /** Takes a string, trims it, removes any extra spaces, 
   * removes diacritics and punctuation, and lowercases it
   * */
  static public function clean(string $string): string
  {
    // Trim the string
    $string = trim($string);

    // Remove HTML tags
    $string = strip_tags($string);

    // Remove extra spaces
    $string = preg_replace('/\s+/', ' ', $string);

    // Normalize the string (remove diacritics)
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

    // Remove punctuation
    $string = preg_replace('/[[:punct:]]/', '', $string);

    // Lowercase the string
    $string = strtolower($string);

    return $string;
  }

  static public function ngrams($str, $n)
  {
    $ngrams = [];
    for ($i = 0; $i < strlen($str) - $n + 1; $i++) {
      $ngrams[] = substr($str, $i, $n);
    }
    return $ngrams;
  }

  static public function ngram_difference($str1, $str2, $n = 2)
  {
    $ngrams1 = StringUtility::ngrams($str1, $n);
    $ngrams2 = StringUtility::ngrams($str2, $n);
    $intersection = count(array_intersect($ngrams1, $ngrams2));
    $union = count(array_unique(array_merge($ngrams1, $ngrams2)));
    return $union == 0 ? 0 : (1 - ($intersection / $union)) * 100;
  }

  static public function jaccard_difference($str1, $str2)
  {
    $str1_chars = str_split($str1);
    $str2_chars = str_split($str2);
    $intersection = count(array_intersect(array_unique($str1_chars), array_unique($str2_chars)));
    $union = count(array_unique(array_merge($str1_chars, $str2_chars)));

    echo var_export($str1_chars) . "\n";
    echo var_export($str2_chars) . "\n";
    echo var_export($intersection) . "\n";
    echo var_export($union) . "\n";

    return $union == 0 ? 0 : (1 - ($intersection / $union)) * 100;
  }

  static public function levenshtein_difference(string $str1, string $str2)
  {
    $levenshtein_distance = levenshtein($str1, $str2);
    $max_length = max(strlen($str1), strlen($str2));
    $difference_percentage = ($levenshtein_distance / $max_length) * 100;
    return $difference_percentage;
  }

  static public function damerau_levenshtein_difference($str1, $str2)
  {
    $len1 = strlen($str1);
    $len2 = strlen($str2);

    // Create a distance matrix
    $distance = array_fill(0, $len1 + 1, array_fill(0, $len2 + 1, 0));

    // Initialize the distance matrix
    for ($i = 0; $i <= $len1; $i++) {
      $distance[$i][0] = $i;
    }
    for ($j = 0; $j <= $len2; $j++) {
      $distance[0][$j] = $j;
    }

    // Fill the distance matrix
    for ($i = 1; $i <= $len1; $i++) {
      for ($j = 1; $j <= $len2; $j++) {
        $cost = ($str1[$i - 1] === $str2[$j - 1]) ? 0 : 1;
        $distance[$i][$j] = min(
          $distance[$i - 1][$j] + 1,
          $distance[$i][$j - 1] + 1,
          $distance[$i - 1][$j - 1] + $cost
        );

        // Consider adjacent transpositions
        if ($i > 1 && $j > 1 && $str1[$i - 2] === $str2[$j - 1] && $str1[$i - 1] === $str2[$j - 2]) {
          $distance[$i][$j] = min($distance[$i][$j], $distance[$i - 2][$j - 2] + 1);
        }
      }
    }

    $d = $distance[$len1][$len2];
    $max_length = max(strlen($str1), strlen($str2));
    $difference_percentage = ($d / $max_length) * 100;
    return $difference_percentage;
  }
}
