<?php

namespace App\Services;

use App\Models\VisitAggregate;
use App\Models\VisitLog;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class VisitTracker
{
    public function __construct(
        private readonly CacheRepository $cache
    ) {
        //
    }

    /**
     * سجل زيارة جديدة وأعد تفاصيل التسجيل.
     *
     * @return array{log: VisitLog, session_id: string, is_new_session: bool, is_unique: bool}
     */
    public function track(Request $request, array $payload = []): array
    {
        $now = Carbon::now();

        $sessionId = $payload['session_id'] ?? $request->cookie('visit_session');
        $isNewSession = false;

        if (empty($sessionId)) {
            $sessionId = (string) Str::uuid();
            $isNewSession = true;
        }

        $ip = $payload['ip'] ?? $request->ip();
        $userAgent = $this->truncate($payload['user_agent'] ?? (string) $request->userAgent(), 512);
        $country = $this->truncate($payload['country'] ?? $request->input('country'), 100);
        $city = $this->truncate($payload['city'] ?? $request->input('city'), 150);
        $path = $this->truncate($payload['path'] ?? $request->input('path', $request->path()), 255);
        $referer = $this->truncate($payload['referer'] ?? $request->headers->get('referer'), 255);

        $ipHash = $ip ? hash('sha256', $ip) : null;
        $fingerprint = $this->generateFingerprint($ipHash, $userAgent, $now);

        $isUnique = $this->shouldMarkAsUnique($fingerprint, $now);

        $log = VisitLog::create([
            'session_id' => $sessionId,
            'fingerprint' => $fingerprint,
            'ip_hash' => $ipHash,
            'user_agent' => $userAgent,
            'country' => $country,
            'city' => $city,
            'path' => $path,
            'referer' => $referer,
            'is_unique' => $isUnique,
            'visited_at' => $now,
        ]);

        $this->updateDailyAggregate($now, $country, $city, $path, $isUnique);

        return [
            'log' => $log,
            'session_id' => $sessionId,
            'is_new_session' => $isNewSession,
            'is_unique' => $isUnique,
        ];
    }

    protected function shouldMarkAsUnique(?string $fingerprint, Carbon $now): bool
    {
        if (!$fingerprint) {
            return false;
        }

        $ttlMinutes = (int) config('services.visits.duplicate_window_minutes', 60 * 24);
        $cacheKey = "visit:fingerprint:{$fingerprint}";

        return $this->cache->add($cacheKey, true, $now->clone()->addMinutes($ttlMinutes));
    }

    protected function updateDailyAggregate(Carbon $date, ?string $country, ?string $city, ?string $path, bool $isUnique): void
    {
        $aggregate = VisitAggregate::query()->firstOrCreate([
            'date' => $date->toDateString(),
            'country' => $country,
            'city' => $city,
            'path' => $path,
        ]);

        $aggregate->increment('visits_count');

        if ($isUnique) {
            $aggregate->increment('unique_visits_count');
        }
    }

    protected function generateFingerprint(?string $ipHash, ?string $userAgent, Carbon $date): ?string
    {
        if (!$ipHash && !$userAgent) {
            return null;
        }

        return hash('sha256', implode('|', [
            $ipHash,
            $userAgent,
            $date->toDateString(),
        ]));
    }

    protected function truncate(?string $value, int $limit): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Str::limit($value, $limit, '');
    }
}

