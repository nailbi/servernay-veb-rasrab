{{-- Общая форма для создания и редактирования статьи.
     old() возвращает введённые значения после ошибки валидации,
     иначе подставляется значение из модели. --}}
@csrf

<div class="form-group">
    <label for="date" class="form-label">Дата</label>
    <input type="date" id="date" name="date"
           value="{{ old('date', $article->date?->format('Y-m-d')) }}"
           class="form-input @error('date') is-invalid @enderror">
    @error('date')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="name" class="form-label">Название</label>
    <input type="text" id="name" name="name" value="{{ old('name', $article->name) }}"
           class="form-input @error('name') is-invalid @enderror" placeholder="Заголовок новости">
    @error('name')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label for="preview_image" class="form-label">Превью</label>
        <select id="preview_image" name="preview_image"
                class="form-input @error('preview_image') is-invalid @enderror">
            <option value="">— выберите —</option>
            @foreach ($images as $image)
                <option value="{{ $image }}" @selected(old('preview_image', $article->preview_image) === $image)>{{ $image }}</option>
            @endforeach
        </select>
        @error('preview_image')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="full_image" class="form-label">Полное изображение</label>
        <select id="full_image" name="full_image"
                class="form-input @error('full_image') is-invalid @enderror">
            <option value="">— выберите —</option>
            @foreach ($images as $image)
                <option value="{{ $image }}" @selected(old('full_image', $article->full_image) === $image)>{{ $image }}</option>
            @endforeach
        </select>
        @error('full_image')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="short_desc" class="form-label">Краткое описание</label>
    <input type="text" id="short_desc" name="short_desc" value="{{ old('short_desc', $article->short_desc) }}"
           class="form-input @error('short_desc') is-invalid @enderror" placeholder="Одно-два предложения">
    @error('short_desc')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="desc" class="form-label">Текст статьи</label>
    <textarea id="desc" name="desc" rows="8"
              class="form-input @error('desc') is-invalid @enderror" placeholder="Полный текст новости">{{ old('desc', $article->desc) }}</textarea>
    @error('desc')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
