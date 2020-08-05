<template>
  <div class="l-main-container">
    <div class="c-main__title">アカウント一覧</div>

    <div class="c-main__function-head">
      <div class="p-account__text-top">
        twitterで「仮想通貨」というキーワードを
        ユーザ名またはプロフィールに記載しているユーザを一覧で表示します。(1日1回更新)
      </div>

      <div>
        <div class="p-account__flex" v-if="isFollowedFlg">
          <i class="fas fa-toggle-on fa-lg fa-fw" @click="autoFollow()"></i>
          <span class="text-color">自動フォロー中です</span>
          <div class="p-account__text-sub p-account__text__attend">※解除するにはoffにしてください</div>
        </div>
        <div class="p-account__flex" v-else>
          <i class="fas fa-toggle-off fa-lg fa-fw" @click="autoFollow()"></i>自動フォローを行います
          <div class="p-account__text-sub p-account__text__attend">※一覧に表示されているアカウントを全て自動でフォローしていきます</div>
        </div>
      </div>

      <div class="p-paginate__wrap">
        <ul class="p-paginate__list">
          <li :class="{disabled: current_page <= 1}" class="p-paginate__left-end">
            <a href="#" @click="change(1)">&laquo;</a>
          </li>
          <li :class="{disabled: current_page <= 1}" class="p-paginate__left">
            <a href="#" @click="change(current_page - 1)">&lt;</a>
          </li>
          <!-- paginate処理 -->
          <li
            :class="{pAccountActive: countAddPage1(pages -4) === current_page}"
            class="p-paginate__page"
          >
            <a href="#" @click="change( countAddPage1(pages -4) )">{{ countAddPage1(pages -4) }}</a>
          </li>
          <li
            :class="{pAccountActive: countAddPage2(pages -3) === current_page}"
            class="p-paginate__page"
          >
            <a href="#" @click="change( countAddPage2(pages -3) )">{{ countAddPage2(pages -3) }}</a>
          </li>
          <li
            :class="{pAccountActive: countAddPage3(pages -2) === current_page}"
            class="p-paginate__page"
          >
            <a href="#" @click="change( countAddPage3(pages -2) )">{{ countAddPage3(pages -2) }}</a>
          </li>
          <li
            :class="{pAccountActive: countAddPage4(pages -1) === current_page}"
            class="p-paginate__page"
          >
            <a href="#" @click="change( countAddPage4(pages -1) )">{{ countAddPage4(pages -1) }}</a>
          </li>
          <li
            :class="{pAccountActive: countAddPage5(pages -0) === current_page}"
            class="p-paginate__page"
          >
            <a href="#" @click="change( countAddPage5(pages -0) )">{{ countAddPage5(pages -0) }}</a>
          </li>
          <!-- paginate処理 ここまで -->
          <li :class="{disabled: current_page >= last_page}" class="p-paginate__right">
            <a href="#" @click="change(current_page + 1)">&gt;</a>
          </li>
          <li :class="{disabled: current_page >= last_page}" class="p-paginate__right-end">
            <a href="#" @click="change(last_page)">&raquo;</a>
          </li>
        </ul>
      </div>
      <div class="p-paginate__navigation">全 {{total}} 件中 {{from}} 〜 {{to}} 件表示</div>
    </div>

    <div class="p-account__panel-wrap" v-for="info in accountdata" v-bind:key="info.index">
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
        <div id="app">
          <button-component v-bind="info" @followEvent="follow(info.id_str)" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data: () => {
    return {
      accountdata: [],
      current_page: 1,
      last_page: 1,
      total: 1,
      from: 0,
      to: 0,
      isFollowedFlg: false,
      loginUserId: "",
      loginUserName: "",
      followCheck: false,
      isFollowing: false,
      autoFlg: false,
    };
  },
  methods: {
    follow(id) {
      console.log(id);
      this.manualFollow(id);
    },
    autoSaveLocalStrage(isFollowedFlg, loginUserId, loginUserName) {
      // データを格納する
      const isFollowedFlgs = JSON.stringify(isFollowedFlg);
      const loginUserIds = JSON.stringify(loginUserId);
      const loginUserNames = JSON.stringify(loginUserName);

      localStorage.setItem("isFollowedFlg", isFollowedFlgs);
      localStorage.setItem("loginUserId", loginUserIds);
      localStorage.setItem("loginUserName", loginUserNames);

      console.log("write");
    },
    autoCatchLocalStrage() {
      // データを呼び出し、一致するか確認　-> 一致すればフラグを更新
      const isFollowedFlgData = localStorage.getItem("isFollowedFlg");
      const loginUserIdData = localStorage.getItem("loginUserId");
      const loginUserNameData = localStorage.getItem("loginUserName");

      const updateFlg = JSON.parse(isFollowedFlgData);
      const updateId = loginUserIdData;
      const updateName = JSON.parse(loginUserNameData);

      const updateTarget = {
        updateFlg: updateFlg,
        updateId: updateId,
        updateName: updateName,
      };
      console.log("localStorageから取得しました");
      return updateTarget;
    },
    async userCheckSessions() {
      const updateTarget = await this.autoCatchLocalStrage();
      //console.log(updateTarget.updateFlg);
      if (
        updateTarget.updateFlg == true &&
        this.loginUserId == updateTarget.updateId &&
        this.loginUserName == updateTarget.updateName
      ) {
        this.isFollowedFlg = updateTarget.updateFlg;
        console.log("read");
      } else {
        console.log(updateTarget);
        console.log("idが違います");
      }
    },
    async getUserAccount() {
      console.log("ユーザー情報を取得します!");
      const usersRes = await axios.get("/auth/users");

      if (usersRes.status == "200") {
        this.loginUserId = usersRes.data.id;
        this.loginUserName = usersRes.data.twitter_name;
        console.log(usersRes.data);
      } else {
        console.log("user get axios is error");
      }
    },
    async manualFollow(key) {
      console.log("manualFollow 実行します!");
      let params = {
        user_id: key,
      };
      const checkRes = await axios.post("/account/followcheck", params);
      // const followRequestSent = checkRes.data.followRequestSent;
      // const following = checkRes.data.following;
      const following = checkRes.data.apiRes[0];
      //console.log(following);

      if (following == "following") {
        console.log("フォローしません");
      } else {
        console.log("フォローします");
        const followRes = await axios.post("/account/follows", params);
        const status = followRes.status;
        if (status == "200") {
          console.log("フォローしました");
        } else {
          alert(
            "フォローに失敗しました。15分以上時間を置いて、再度実行してください。"
          );
        }
      }
    },
    async autoFollow() {
      if (this.isFollowedFlg === false) {
        // on
        if (confirm("フォローを自動で実行しますか？（中断も可能です）")) {
          this.isFollowedFlg = !this.isFollowedFlg;
          // ログインユーザーのidを渡す
          //const params = { loginId: this.loginUserId };
          // API実行
          //const autoFollow = await axios.post("/account/autofollows", params);
          // データ受け取り
          //const autoFollowRes = autoFollow.data;
          //console.log(autoFollowRes);
          //if(autoFollowRes == "following"){
          //  this.autoFlg = true;
          //};
          this.loginUserName = this.loginUserName;
          this.loginUserId = this.loginUserId;
          await this.autoSaveLocalStrage(
            this.isFollowedFlg,
            this.loginUserId,
            this.loginUserName
          );
        }
      } else {
        // off
        if (confirm("フォローを中断しますか？")) {
          this.isFollowedFlg = !this.isFollowedFlg;
          //const params = { user_id: this.loginUserId };
          //const autoFollow = await axios.post("/account/autofollows", params);
          //const autoFollowRes = autoFollow.data.following;
          //console.log(autoFollowRes);
          //if(autoFollowRes == "following"){
          //  this.autoFlg = false;
          //};
          this.loginUserName = this.loginUserName;
          this.loginUserId = this.loginUserId;
          await this.autoSaveLocalStrage(
            this.isFollowedFlg,
            this.loginUserId,
            this.loginUserName
          );
        }
      }
    },
    async followingCheckApi() {
      console.log("check!!");
      axios
        .get("/account/user/followcheck")
        .then((res) => {
          const isFollow = res.data;
          console.log(res.data);
        })
        .catch((error) => {
          console.log(response.error.data.following);
        });
    },
    load(page) {
      axios.get("/api/account?page=" + page).then(({ data }) => {
        this.accountdata = data.data;
        this.current_page = data.current_page;
        this.last_page = data.last_page;
        this.total = data.total;
        this.from = data.from;
        this.to = data.to;
      });
    },
    change(page) {
      if (page >= 1 && page <= this.last_page) this.load(page);
      console.log("async action");
    },
    countAddPage1(page) {
      if (this.current_page >= 6) {
        page = this.current_page - 4;
        return page;
      }
      return page;
    },
    countAddPage2(page) {
      if (this.current_page >= 6) {
        page = this.current_page - 3;
        return page;
      }
      return page;
    },
    countAddPage3(page) {
      if (this.current_page >= 6) {
        page = this.current_page - 2;
        return page;
      }
      return page;
    },
    countAddPage4(page) {
      if (this.current_page >= 6) {
        page = this.current_page - 1;
        return page;
      }
      return page;
    },
    countAddPage5(page) {
      if (this.current_page >= 6) {
        page = this.current_page;
        return page;
      }
      return page;
    },
    delay(timeout) {
      return new Promise((resolve) => {
        setTimeout(resolve, timeout);
      });
    },
  },
  computed: {
    pages() {
      let end = 5;
      return end;
    },
  },
  mounted() {
    this.load(1);
    // this.followingCheckApi();// ここで呼ぶ
  },
  async created() {
    await this.getUserAccount();
    //this.delay(1000);
    await this.userCheckSessions();
  },
};
</script>

