<template>
  <div class="l-main-container">
    <div class="c-main__title">{{ ('Trend Account Index') }}</div>

    <div class="c-main__function-head">
      <div class="p-account__text">
        twitterで「仮想通貨」というキーワードを
        ユーザ名またはプロフィールに記載しているユーザを一覧で表示します。(1日1回更新)
      </div>

      <button class="p-btn p-btn__follow" v-on:click="testFollow()">
        <a><i class="fab fa-twitter"></i>テスト用</a>
      </button>

      <div class="p-account__flex">
        <input type="checkbox" v-model="isChecked" />自動フォロー
        <button class="p-btn p-btn__follow p-btn__autobtn" v-on:click="autoFollow()">実行</button>
      </div>

      <div class="p-account__text p-account__text__attend">※一覧に表示されているアカウントを全て自動でフォローしていきます</div>
    </div>

    <div class="p-account__panel-wrap" v-for="info in accountdata.data" v-bind:key="info.index">
      <div class="p-account__inner">
        <div class="p-account__section-top">
          <div class="p-account__inner2">
            <div class="p-account__name">{{ info.name }}</div>
            <div class="p-account__username">{{ info.screen_name }}</div>
          </div>
          <div class="p-account__inner3">
            <div class="p-account__title">フォロー</div>
            <div class="p-account__follow">{{ info.friends_count }}</div>
          </div>
          <div class="p-account__inner4">
            <div class="p-account__title">フォロワー</div>
            <div class="p-account__follow">{{ info.followers_count }}</div>
          </div>
        </div>
        <div class="p-account__section-middle">
          <div class="p-account__prof">{{ info.description }}</div>
        </div>
        <div class="p-account__section-bottom">
          <div class="p-account__tweet">{{ info.text }}</div>
        </div>
        <button class="p-btn p-btn__follow" v-on:click="manualFollow()">
          <a>
            <i class="fab fa-twitter"></i>フォローする
          </a>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  //["accountdata"],
  props: {
    accountdata: {
      type: Object
    }
  },
  name: "accountdata",
  data: () => {
    return {
      accountdata: this.accountdata, //propsを直接操作するのはNGなので、データとして保持
      errored: false,
      isChecked: false,
      targetId: '1252388152221655049'
    };
  },
  // created(){
  //     axios.get('./account?page=1')
  //     .then(res => {
  //       this.accountdata = res.data;
  //     })
  //     .catch(error => {
  //       console.log(error);
  //     });
  // }
  methods: {
    testFollow() {
      console.log("Followed!");
      axios
        .post('friendships/create', {
          params: {
            user_id: this.targetId
          }
        })
        .then(res => {
          this.accountdata = this.res.data;
          console.log("success!");
        })
        .catch(error => {
          this.errored = true;
          console.log("error!");
        });

    },
    manualFollow() {
      console.log("manualFollowed!");
      // 選択したvalue値をkeywordとして送信
      // axios
      //   .get(`/trend/search?q=${keyword}`)
      //   .then(res => {
      //     this.trendData = this.res.data;
      //     this.keyword = this.res.keyword;
      //   })
      //   .catch(error => {
      //     this.errored = true;
      //   });
    },
    autoFollow() {
      if (this.isChecked === true) {
        if (confirm("自動フォローを実行しますか？")) {
          console.log("running!!");
          //実行処理
        } else {
          console.log("false");
          //処理しない
        }
      } else {
        alert("Checkしてください");
        //処理しない
      }
    }
  }
};
</script>
