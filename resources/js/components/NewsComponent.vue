<template>
  <div class="p-news__section">
    <table class="table">
      <thead>
        <tr class="p-news__tr" style="background-color: #C5CAE9; font-size: 14px;">
          <th class="p-news__th-icon">
            <i class="far fa-clock"></i>
          </th>
          <th class="p-news__th-label">
            <div>更新日時</div>
          </th>
          <th class="p-news__th-data">{{ newsline[0].lastBuildDate.substr(0, 16) }}取得</th>
        </tr>
      </thead>
    </table>

    <div class="p-news__body">
      <div class="p-news__panel-wrap" v-for="news in newsline" :key="news.index">
        <div class="p-news__inner">
          <a class="p-news__newstitle" v-bind:href="news.link[0]" target="”_blank”">{{ news.title }}</a>
          <div class="p-news__news-inner2">
            <p class="p-news__newssource">{{ news.source }}</p>
            <p class="p-news__newspubDate">{{ news.pubDate }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    newsline: {},
  },
  data: () => {
    return {
      getTimes: "",
    };
  },
  computed: {
    dateExchange() {
      // 日付変換
      const getDates = this.newsline[0].lastBuildDate;
      const year = getDates.substr(0, 4);
      const month = getDates.substr(5, 2);
      const days = getDates.substr(8, 2);
      const hours = getDates.substr(11, 2);
      const times = getDates.substr(14, 2);
      const Dates =
        year +
        "年" +
        month +
        "月" +
        days +
        "日" +
        " " +
        hours +
        "月" +
        times +
        "時" +
        " 更新";
      this.getTimes = Dates;
    },
  },
};
</script>
