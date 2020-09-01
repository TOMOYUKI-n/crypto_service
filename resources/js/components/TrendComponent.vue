<template>
  <div class="l-main-container">
    <div class="c-main__title">{{ '仮想通貨トレンド一覧' }}</div>

    <div class="c-main__function-head">
      <div class="p-trend__time">
        <div class="no-padding" style="display: flex;">
          <p style="margin-right: 5px;">
            <i class="far fa-clock"></i>
          </p>
          <p>{{ getTimes }}</p>
        </div>
      </div>
      <div class="p-trend__search">
        <div class="p-trend__search-time">
          <p class="p-trend__search-inner">{{ '日時で絞込む' }}</p>
          <div>
            <v-select
              class="p-trend__search-time__select p-trend__select"
              :options="options"
              label="times"
              v-model="selected"
              :reduce="options => options.id"
            />
            <button class="filterBtn2" v-on:click="getSearch()">
              <i class="fas fa-filter"></i>
            </button>
          </div>
        </div>
        <div class="p-trend__search-coin" style="height: 90px;">
          <p class="p-trend__search-inner">{{ '銘柄で絞込む' }}</p>
          <div>
            <v-select
              multiple
              :options="options2"
              v-model="selected2"
              class="p-trend__search-coin__select p-trend__select"
            />
            <button class="filterBtn2" v-on:click="filtered()">
              <i class="fas fa-funnel-dollar"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <section v-if="errored" class="p-trend__errorArea">
      <div>情報が取得できませんでした。再度時間を置いて更新してください。</div>
    </section>

    <div class="p-trend__list-wrap">
      <table class="table">
        <!-- 見出し -->
        <thead>
          <tr class="p-trend__list-header" style="background-color: #C5CAE9">
            <th class="p-trend__list__item p-trend-map">{{ '銘柄' }}</th>
            <th class="p-trend__list__tweet p-trend-map">{{ 'Tweet数' }}</th>
            <th class="p-trend__list__high p-trend-map">{{ '最高取引レート(円)' }}</th>
            <th class="p-trend__list__low p-trend-map">{{ '最低取引レート(円)' }}</th>
          </tr>
        </thead>

        <!-- ランキング部分 -->
        <tbody>
          <tr v-for="trend in trends" v-bind:key="trend.index" class="p-trend__list-title">
            <td class="p-trend__list__item p-trend__link">
              <a v-bind:href="url+trend.coin_name" target="”_blank”">{{ trend.coin_name }}</a>
            </td>
            <td class="p-trend__list__tweet">{{ trend.tweet }}</td>
            <td class="p-trend__list__high">{{ trend.high }}</td>
            <td class="p-trend__list__low">{{ trend.low }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import axios from "axios";
import "vue-select/dist/vue-select.css"; //複数選択にて利用
export default {
  components: {
    vSelect,
  },
  data: () => {
    return {
      trends: [],
      url: "https://twitter.com/search?q=",
      getTimes: "",
      apiUrl: "",
      keyword: "",
      selected: "",
      options: [
        { times: "過去1時間", id: "1hour" },
        { times: "過去1日間", id: "1day" },
        { times: "過去1週間", id: "1week" },
      ],
      errored: false,
      selected2: [],
      options2: [
        "BTC",
        "ETH",
        "ETC",
        "LSK",
        "FCT",
        "XRP",
        "XEM",
        "LTC",
        "BCH",
        "MONA",
        "XLM",
        "QTUM",
        "DASH",
        "ZEC",
        "XMR",
        "REP",
      ],
    };
  },
  methods: {
    dateExchange() {
      // 日付変換
      const getDates = this.trends[0].get_dates;
      console.log(getDates);
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
        "時" +
        times +
        "分" +
        " 更新";
      this.getTimes = Dates;
    },
    async getSearch() {
      // トレンドのデータを取得
      // 初期遷移時は　選択項目がnullのため指定
      if (this.selected === "") {
        this.keyword = "1hour";
      } else {
        this.keyword = this.selected;
      }
      // URL定義
      let apiUrl = "";
      if (this.keyword === "1hour") {
        apiUrl = "/api/trend";
      } else if (this.keyword === "1day") {
        apiUrl = "/api/trend/day";
      } else if (this.keyword === "1week") {
        apiUrl = "/api/trend/week";
      }

      // apiにてデータ取得
      try {
        const responce = await axios.get(apiUrl, {
          params: {
            q: this.keyword,
          },
        });
        const data = responce.data;
        this.trends = data;
        this.errored = false;
        // 日付変換処理を呼び出す
        this.dateExchange();
        this.saveLocalStorage();
      } catch (ex) {
        this.errored = true;
        // localstorageから取得
        this.getLocalStorage();
        this.dateExchange();
      }
    },
    saveLocalStorage() {
      const trendList = JSON.stringify(this.trends);
      localStorage.setItem("trendList", trendList);
    },
    getLocalStorage() {
      // データを呼び出し、一致するか確認　-> 一致すればフラグを更新
      const trendList = localStorage.getItem("trendList");
      this.trends = JSON.parse(trendList);
    },
    async filtered() {
      await this.getSearch();
      var result = [];
      // 選択された銘柄が、データの中にあるか否かを、照らし合わせる（選択銘柄分リピート）
      for (var i = 0; i < this.selected2.length; i++) {
        result[i] = this.trends.filter(
          (x) => x["coin_name"] === this.selected2[i]
        );
      }

      // ネストされた配列のフラット化
      var resultData = result.flat();
      this.trends = resultData;
      // console.log(this.trends);
      // sortして降順でランキング状態にしてから表示させる
      return this.trends.sort((a, b) => {
        return a.tweet > b.tweet ? -1 : 0;
      });
    },
  },
  mounted() {
    this.getSearch();
  },
};
</script>
