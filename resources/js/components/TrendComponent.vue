<template>
  <div class="l-main-container">
    <div class="c-main__title">{{ '仮想通貨トレンド一覧' }}</div>

    <div class="c-main__function-head">
      <div class="p-trend__time">
        <p class="no-padding">情報取得日時：</p>
      </div>

      <div class="p-trend__search">
        <div class="p-trend__search-time">
          <p class="p-trend__search-inner">{{ '日時で絞込む' }}</p>
          <form class>
            <select
              class="p-trend__search-time__select"
              aria-label="Search"
              v-model="keyword"
              name="keyword"
              value="keyword"
            >
              <option value="1hour" selected>過去1時間でのランキング</option>
              <option value="1day">過去1日間でのランキング</option>
              <option value="1week">過去1週間でのランキング</option>
            </select>
            <button type="submit" v-on:click="getSearch()">Search</button>
          </form>
        </div>
        <div class="p-trend__search-name">
          <p class="p-trend__search-inner">{{ '銘柄で絞込む' }}</p>
          <v-select multiple :options="options" v-model="selected" />
          <button v-on:click="filtered()">絞り込む</button>
        </div>
      </div>
    </div>

    <div class="p-trend__list-wrap">
      <!-- 見出し -->
      <div class="p-trend__list-title">
        <div class="p-trend__list__item p-trend-map">{{ '銘柄' }}</div>
        <div class="p-trend__list__tweet p-trend-map">{{ 'Tweet数' }}</div>
        <div class="p-trend__list__high p-trend-map">{{ '最高取引レート(円)' }}</div>
        <div class="p-trend__list__low p-trend-map">{{ '最低取引レート(円)' }}</div>
      </div>
      <!-- ランキング部分 -->
      <section v-if="errored" class="p-trend__errorArea">
        <div class="p-trend">情報が取得できませんでした。再度時間を置いて更新してください。</div>
      </section>

      <section v-else>
        <div v-for="trend in trendData" v-bind:key="trend.index" class="p-trend__list-title">
          <div class="p-trend__list__item">{{ trend.coin_name }}</div>
          <div class="p-trend__list__tweet">{{ trend.tweet }}</div>
          <div class="p-trend__list__high">{{ trend.high }}</div>
          <div class="p-trend__list__low">{{ trend.low }}</div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import axios from "axios";
import "vue-select/dist/vue-select.css"; //複数選択にて利用
export default {
  components: {
    vSelect
  },
  props: ["trends"],
  name: "trends",
  data: function() {
    return {
      trendData: this.trends, //propsを直接操作するのはNGなので、データとして保持
      keyword: "1hour",
      errored: false,
      selected: [],
      options: [
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
        "REP"
      ]
    };
  },
  methods: {
    getSearch() {
      if (this.keyword !== "" || this.keyword !== null) {
        // 選択したvalue値をkeywordとして送信
        axios
          .get(`/trend/search?q=${keyword}`)
          .then(res => {
            // if (res.status == 404) {
            //   console.log(res);
            //   this.trendData = "";
            //   this.errored = true;
            // }
            this.trendData = this.res.data;
            this.keyword = this.res.keyword;
            console.log(res.data);
          })
          .catch(error => {
            this.errored = true;
          });
      }
    },
    filtered() {
      var result = [];
      // 選択された銘柄が、データの中にあるか否かを、照らし合わせる（選択銘柄分リピート）
      for (var i = 0; i < this.selected.length; i++) {
        result[i] = this.trends.filter(
          word => word["coin_name"] == this.selected[i]
        );
      }
      // ネストされた配列のフラット化
      var resultData = result.flat();
      this.trendData = resultData;
      // sortして降順でランキング状態にしてから表示させる
      return this.trendData.sort((a, b) => {
        return a.tweet > b.tweet ? -1 : 0;
      });
    }
  },
  created() {
    //localstrageに格納
    if (
      this.trends == "" ||
      this.trends == null ||
      this.trends == undefined
    )
     {
      this.trendData = [];
      this.errored = true;
      // localstrageからの呼び出し処理

    } else {
      console.log("there is trends");
    　　// localstrageへの登録処理
    }
  }
};
</script>
