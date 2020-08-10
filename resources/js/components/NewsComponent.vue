<template>

    <div class="p-news__section">
        <div class="c-main__function-head">
            
            <div class="p-trend__time">
               更新日時 {{ newsline[0].lastBuildDate }}
            </div>

        </div>
        <div class="p-news__panel-wrap" v-for="news in newsline" v-bind:key="news.index">
            <div class="p-news__inner">
                <a class="p-news__newstitle" v-bind:href="news.link[0]" target=”_blank”>{{ news.title }}</a>

                <div class="p-news__news-inner2">
                    <p class="p-news__newssource ">{{ news.source }}</p>
                    <p class="p-news__newspubDate">{{ news.pubDate }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        newsline: {}
    }
}
</script>
