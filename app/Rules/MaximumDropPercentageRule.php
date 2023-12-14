<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaximumDropPercentageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sum = array_sum(array_map(function ($item) {
            return $item['drop_percentage'] ?? 0;
        }, $value));

        if ($sum !== 100 && $value !== []) {
            $fail('The sum of all drop percentages must be 100.');
        }
    }
}
