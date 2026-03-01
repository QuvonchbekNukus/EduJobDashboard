<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Vacancy;
use App\Models\VacancyPost;
use RuntimeException;

class TelegramVacancyPublisher
{
    /**
     * Publish vacancy to Telegram channel and persist post mapping.
     *
     * @throws \RuntimeException
     */
    public function publish(Vacancy $vacancy): VacancyPost
    {
        if ($vacancy->status !== 'published') {
            throw new RuntimeException('Only published vacancies can be sent to Telegram.');
        }

        $channel = Channel::query()
            ->where('region_id', $vacancy->region_id)
            ->where('is_active', true)
            ->first();

        if (! $channel) {
            throw new RuntimeException("No active Telegram channel found for region_id={$vacancy->region_id}.");
        }

        $chatId = (int) $channel->tg_chat_id;

        try {
            // PSEUDO-CODE:
            // $text = $this->buildVacancyMessage($vacancy);
            // $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            //     'chat_id' => $chatId,
            //     'text' => $text,
            //     'parse_mode' => 'HTML',
            // ]);
            // throw_if(! $response->ok(), new RuntimeException('Telegram API error.'));
            // $messageId = data_get($response->json(), 'result.message_id');
            // throw_if(! $messageId, new RuntimeException('Telegram message_id was not returned.'));

            // Skeleton value for now. Replace with real Telegram response parsing.
            $messageId = 0;
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to publish vacancy to Telegram: '.$e->getMessage(), 0, $e);
        }

        if ($messageId <= 0) {
            throw new RuntimeException('Invalid tg_message_id returned by Telegram.');
        }

        return VacancyPost::updateOrCreate(
            ['vacancy_id' => $vacancy->id],
            [
                'tg_chat_id' => $chatId,
                'tg_message_id' => $messageId,
                'posted_at' => now(),
            ]
        );
    }
}
