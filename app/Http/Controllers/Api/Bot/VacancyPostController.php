<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\VacancyPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VacancyPostController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $query = VacancyPost::query()
            ->with(['vacancy.employer', 'vacancy.subject'])
            ->orderByDesc('id');

        $vacancyId = (int) $request->query('vacancy_id', 0);
        if ($vacancyId > 0) {
            $query->where('vacancy_id', $vacancyId);
        }

        $chatId = $request->query('tg_chat_id');
        if ($chatId !== null && $chatId !== '') {
            $query->where('tg_chat_id', (int) $chatId);
        }

        $vacancyPosts = $query->paginate($this->perPage($request));

        return $this->paginated($vacancyPosts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules($request));

        $vacancyPost = VacancyPost::create($this->payload($validated));

        return $this->success($this->loadRelations($vacancyPost), 201);
    }

    public function show(VacancyPost $vacancyPost): JsonResponse
    {
        return $this->success($this->loadRelations($vacancyPost));
    }

    public function update(Request $request, VacancyPost $vacancyPost): JsonResponse
    {
        $validated = $request->validate($this->rules($request, $vacancyPost));

        $vacancyPost->update($this->payload($validated, $vacancyPost));

        return $this->success($this->loadRelations($vacancyPost));
    }

    public function destroy(VacancyPost $vacancyPost): JsonResponse
    {
        $vacancyPost->delete();

        return $this->deleted();
    }

    private function rules(Request $request, ?VacancyPost $vacancyPost = null): array
    {
        return [
            'vacancy_id' => [
                'required',
                'integer',
                'exists:vacancies,id',
                Rule::unique('vacancy_posts', 'vacancy_id')->ignore($vacancyPost?->id),
            ],
            'tg_chat_id' => ['required', 'integer'],
            'tg_message_id' => [
                'required',
                'integer',
                Rule::unique('vacancy_posts', 'tg_message_id')
                    ->where(fn ($query) => $query->where('tg_chat_id', (int) $request->input('tg_chat_id')))
                    ->ignore($vacancyPost?->id),
            ],
            'posted_at' => ['nullable', 'date'],
        ];
    }

    private function payload(array $validated, ?VacancyPost $vacancyPost = null): array
    {
        $payload = [
            'vacancy_id' => $validated['vacancy_id'],
            'tg_chat_id' => $validated['tg_chat_id'],
            'tg_message_id' => $validated['tg_message_id'],
        ];

        if (array_key_exists('posted_at', $validated)) {
            $payload['posted_at'] = $validated['posted_at'];
        } elseif ($vacancyPost) {
            $payload['posted_at'] = $vacancyPost->posted_at;
        }

        return $payload;
    }

    private function loadRelations(VacancyPost $vacancyPost): VacancyPost
    {
        return $vacancyPost->load(['vacancy.employer', 'vacancy.subject']);
    }
}
