<template>
    <!-- Всплывающее уведомление показывается, только если по каналу пришли данные -->
    <div v-if="article != null" class="push-alert" role="alert">
        <span>
            Добавлена новая статья
            <strong><a :href="`/articles/${article.id}`">{{ article.name }}</a></strong>
        </span>
        <button type="button" class="push-alert__close" aria-label="Закрыть" @click="article = null">×</button>
    </div>
</template>

<script>
export default {
    data() {
        return { article: null };
    },
    created() {
        if (!window.Echo) {
            return;
        }

        // Слушаем событие App\Events\NewArticleEvent на публичном канале test.
        window.Echo.channel('test').listen('NewArticleEvent', (data) => {
            console.log(data);
            this.article = data.article;
            // alert('Добавлена новая статья!');
        });
    },
};
</script>
