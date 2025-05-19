<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewCounterService
{
    /**
     * Обработать просмотр комикса и вернуть флаг, если счётчик должен быть увеличен.
     *
     * @param \App\Models\AuthorComics $authorComic
     * @param int $viewLimitInterval
     * @return bool
     */
    public function handleView($authorComic, $viewLimitInterval)
    {
        $userIdentifier = Auth::check() ? Auth::id() : session()->getId();
        $lastViewedKey = 'last_viewed_' . $authorComic->id . '_' . $userIdentifier;

        $lastViewed = session($lastViewedKey);
        $shouldIncrement = false;

        if (!$lastViewed) {
            $shouldIncrement = true;
//            Log::info("First view for comic ID: {$authorComic->id}");
        } else {
            $nextAllowedView = (clone $lastViewed)->addMinutes($viewLimitInterval);
            $remainingSeconds = now()->diffInSeconds($nextAllowedView, false);

            if ($remainingSeconds <= 0) {
                $shouldIncrement = true;
//                Log::info("View interval passed for comic ID: {$authorComic->id}");
            } else {
                $remainingMinutes = max(0, $remainingSeconds) / 60;
//                Log::info("Time remaining until next view: " . round($remainingMinutes, 2) . " minutes");
            }
        }

        if ($shouldIncrement) {
            $authorComic->increment('views');
            session([$lastViewedKey => now()]);
//            Log::info("Incremented views for comic ID: {$authorComic->id}. New count: " . ($authorComic->views + 1));
        }

        return $shouldIncrement;
    }
}
