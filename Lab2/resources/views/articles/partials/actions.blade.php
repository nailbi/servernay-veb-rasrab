{{-- Кнопки управления статьёй: редактировать и удалить --}}
<div class="actions">
    <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm">Изменить</a>

    {{-- HTML-формы умеют только GET/POST, поэтому DELETE передаём через @method --}}
    <form action="{{ route('articles.destroy', $article) }}" method="POST"
          onsubmit="return confirm('Удалить эту новость?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
    </form>
</div>
