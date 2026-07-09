<?php

namespace App\Support;

class YoutubeUrlParser
{
    /** @return array{video_id: string, thumbnail_url: string}|null */
    public static function parse(?string $url): ?array
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        $videoId = self::extractVideoId($url);

        if ($videoId === null) {
            return null;
        }

        return [
            'video_id' => $videoId,
            'thumbnail_url' => 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg',
        ];
    }

    public static function extractVideoId(string $url): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/watch\?.*v=|youtube\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('~^([A-Za-z0-9_-]{11})$~', $url, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
