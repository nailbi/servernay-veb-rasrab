@extends('layouts.app')

@section('title', 'Модерация комментариев')

@section('content')
    <section class="page">
        <div class="container">
            <p class="eyebrow">Панель модератора</p>
            <h1 class="page-title">Комментарии на модерации</h1>

            {{-- Только комментарии с is_approved = false (CommentController::moderation) --}}
            @if ($comments->isNotEmpty())
                <table class="articles">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Статья</th>
                            <th>Автор</th>
                            <th>Комментарий</th>
                            <th>Решение</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                            <tr>
                                <td class="a-date">{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                <td class="a-name">
                                    <a href="{{ route('articles.show', $comment->article) }}">{{ $comment->article->name }}</a>
                                </td>
                                <td>{{ $comment->user->name }}</td>
                                <td class="a-desc">{{ $comment->text }}</td>
                                <td>
                                    <div class="actions">
                                        {{-- Принять: PATCH comments/{comment}/approve --}}
                                        <form action="{{ route('comments.approve', $comment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-solid">Принять</button>
                                        </form>

                                        {{-- Отклонить: DELETE comments/{comment}/reject --}}
                                        <form action="{{ route('comments.reject', $comment) }}" method="POST"
                                              onsubmit="return confirm('Отклонить и удалить комментарий?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Отклонить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="prose">Новых комментариев нет — всё проверено.</p>
            @endif
        </div>
    </section>
@endsection
