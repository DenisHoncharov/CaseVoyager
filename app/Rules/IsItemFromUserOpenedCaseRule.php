<?php

namespace App\Rules;

use App\Models\OpenCaseResult;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsItemFromUserOpenedCaseRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = app()->make('getUserFromDBUsingAuth0');
        $items = collect($value);

        $openCaseResults = OpenCaseResult::whereIn('id', $items->pluck('openCaseResultId'))
            ->where('is_received', false)
            ->get();

        if ($openCaseResults->isEmpty()) {
            $fail('The open case result does not exist.');
        }

        $openCaseResults->each(function ($openCaseResult) use ($items, $user, $fail) {
            $item = $items->firstWhere('openCaseResultId', $openCaseResult->id);

            if (is_null($item)) {
                $fail('The open case result does not exist.');
            }

            if ($item['item_id'] !== $openCaseResult->item_id) {
                $fail('The item does not belong to the open case result.');
            }

            if ($openCaseResult->user_id !== $user->id) {
                $fail('The open case result does not belong to the user.');
            }
        });
    }
}
