<template>
  <div class="l-main-container">
    <div class="c-main__title">アカウント一覧</div>
    <div class="c-main__function-head">
      <div class="p-account__text-top-icon">
        <i class="fas fa-address-book"></i>
        <p class="p-account__text-top">
          仮想通貨に関連するアカウントをフォローできます。(1日1回更新)
        </p>
      </div>
      <div class="c-alert__caution1" style="font-size: 13px;">
        <p v-if="loginFromTwitter == false">フォロー機能をご利用の際は、twitterアカウントでログインしてください</p>
      </div>

      <table class="table">
        <!-- 見出し -->
        <thead>
          <tr class="p-account__tr">
            <th class="p-account__th" v-if="loginFromTwitter">
              <div class="p-account__flex" v-if="isFollowedFlg">
                <div class="p-btn__autofollow">
                  <i class="fas fa-toggle-on fa-lg fa-fw" @click="autoFollow()"></i>
                </div>
                <div class="p-account__autobtn text-color">自動フォロー中</div>
              </div>
              <div class="p-account__flex" v-else>
                <div class="p-btn__autofollow">
                  <i class="fas fa-toggle-off fa-lg fa-fw" @click="autoFollow()"></i>
                </div>
                <div
                  class="p-account__autobtn"
                >自動フォロー</div>
              </div>
            </th>
            <th class="p-account__th">
              <div class="p-paginate__navigation">全 {{total}} 件中 {{from}} 〜 {{to}} 件表示</div>
            </th>
            <th class="p-account__th">
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
                    <a
                      href="#"
                      @click="change( countAddPage1(pages -4) )"
                    >{{ countAddPage1(pages -4) }}</a>
                  </li>
                  <li
                    :class="{pAccountActive: countAddPage2(pages -3) === current_page}"
                    class="p-paginate__page"
                  >
                    <a
                      href="#"
                      @click="change( countAddPage2(pages -3) )"
                    >{{ countAddPage2(pages -3) }}</a>
                  </li>
                  <li
                    :class="{pAccountActive: countAddPage3(pages -2) === current_page}"
                    class="p-paginate__page"
                  >
                    <a
                      href="#"
                      @click="change( countAddPage3(pages -2) )"
                    >{{ countAddPage3(pages -2) }}</a>
                  </li>
                  <li
                    :class="{pAccountActive: countAddPage4(pages -1) === current_page}"
                    class="p-paginate__page"
                  >
                    <a
                      href="#"
                      @click="change( countAddPage4(pages -1) )"
                    >{{ countAddPage4(pages -1) }}</a>
                  </li>
                  <li
                    :class="{pAccountActive: countAddPage5(pages -0) === current_page}"
                    class="p-paginate__page"
                  >
                    <a
                      href="#"
                      @click="change( countAddPage5(pages -0) )"
                    >{{ countAddPage5(pages -0) }}</a>
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
            </th>
          </tr>
        </thead>
      </table>
    </div>

    <!--一覧表示エリア-->
    <div class="container" v-if="isLoading">
      <div>Loading...</div>
    </div>
    <div class="container"  v-if="getStatus">
      <div>アカウント情報を取得できませんでした。時間をおいて更新をしてください。</div>
    </div>
    <div id="app" v-else>
      <transition-group name="list" tag="div">
        <div v-for="(info,key) in accountdata" :key="info.id_str">
          <button-component :info="info" @followEvent="follow(key, info, current_page)" :disableFollowBtn="disableFollowBtn" :loginFromTwitter="loginFromTwitter" />
        </div>
      </transition-group>
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
      disableFollowBtn: false,
      autoFlg: false,
      isLoading: false,
      getStatus: false,
      loginFromTwitter: true,
    };
  },
  methods: {
    async follow(key, info, current_page) {
      // フォロー処理を呼ぶ
      this.manualFollow(info.id_str);
      // 強制的にリロードする
      await this.accountdata.splice(key, 1);
      await this.delay(4000);
      await this.load(current_page);
      console.log("follow処理が実行されました");
    },
    autoSaveLocalStrage(isFollowedFlg, loginUserId, loginUserName) {
      // localstorageにデータを格納する
      const isFollowedFlgs = JSON.stringify(isFollowedFlg);
      const loginUserIds = JSON.stringify(loginUserId);
      const loginUserNames = JSON.stringify(loginUserName);

      localStorage.setItem("isFollowedFlg", isFollowedFlgs);
      localStorage.setItem("loginUserId", loginUserIds);
      localStorage.setItem("loginUserName", loginUserNames);
      console.log("write");
    },
    autoCatchLocalStrage() {
      // localstorageからデータを呼び出し、一致するか確認　-> 一致すればフラグを更新
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
      // localstorageにあるデータがログインしているユーザーと一緒かどうかチェック
      if (
        updateTarget.updateFlg == true &&
        this.loginUserId == updateTarget.updateId &&
        this.loginUserName == updateTarget.updateName
      ) {
        this.isFollowedFlg = updateTarget.updateFlg;
      } else {
        console.log(updateTarget);
        console.log("idが違うか自動フォローがoffになっています");
      }
    },
    async getUserAccount() {
      console.log("ユーザー情報を取得します!");
      const usersRes = await axios.get("/auth/users");

      if (usersRes.status == "200") {
        this.loginUserId = usersRes.data.id;
        this.loginUserName = usersRes.data.twitter_name;
        // もしトークンがない場合（twitterでのログインでなければ）フォローボタンを表示しない
        if(usersRes.data.twitter_oauth_token === null ){
          this.loginFromTwitter = false;
        }else{
          this.loginFromTwitter = true;
        }
        console.log(this.loginFromTwitter);
      } else {
        console.log("user get axios is error");
      }
    },
    async manualFollow(key) {
      console.log("手動Follow 実行します!");
      let params = {
        user_id: key,
      };
      const checkRes = await axios.post("/account/followcheck", params);
      const following = checkRes.data.apiRes[0];
      console.log(following);

      if (following == 1) {
        alert(
          "フォローに失敗しました。15分以上時間を置いて、再度実行してください。"
        );
      } else {
        if (following == "following") {
          console.log("フォローしません");
        } else {
          console.log("フォローします");
          const followRes = await axios.post("/account/follows", params);
          const status = followRes.status;
          if (status == "200") {
            alert("フォローしました");
          } else {
            alert(
              "フォローに失敗しました。15分以上時間を置いて、再度実行してください。"
            );
          }
        }
      }
    },
    async autoFollow() {
      if (this.isFollowedFlg === false) {
        // on
        if (confirm("フォローを自動で実行しますか？（中断も可能です）")) {
          this.isFollowedFlg = !this.isFollowedFlg;
          this.disableFollowBtn = !this.disableFollowBtn;
          // ログインユーザーのidを渡す
          const params = { loginId: this.loginUserId };
          // API実行
          const autoFollow = await axios.post("/account/autofollows", params);
          // データ受け取り
          const autoFollowRes = autoFollow.data;
          // レスポンス結果が following　であれば autoDBに　1　を登録
          if (autoFollowRes == "following") {
            this.autoFlg = true;
          }
          // フラグを更新
          this.loginUserName = this.loginUserName;
          this.loginUserId = this.loginUserId;
          // ローカルストレージに保存
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
          this.disableFollowBtn = !this.disableFollowBtn;
          const params = { user_id: this.loginUserId };
          // 自動フォロー対象から外すために、フラグを更新する処理を行う
          const autoFollow = await axios.post("/account/autofollows", params);
          const autoFollowRes = autoFollow.data.following;
          console.log(autoFollowRes);
          //　処理が正常に完了したらフラグを変更する
          if (autoFollowRes == "following") {
            this.autoFlg = false;
          }
          // 各種フラグ更新
          this.loginUserName = this.loginUserName;
          this.loginUserId = this.loginUserId;
          // ローカルストレージに保存
          await this.autoSaveLocalStrage(
            this.isFollowedFlg,
            this.loginUserId,
            this.loginUserName
          );
        }
      }
    },
    load(page) {
        // アカウント情報を表示する
        axios.get("/api/account?page=" + page).then(({ data }) => {
          this.accountdata = data.data;
          this.current_page = data.current_page;
          this.last_page = data.last_page;
          this.total = data.total;
          this.from = data.from;
          this.to = data.to;
          this.getStatus = false;
        })
        .catch( () => {
          this.getStatus = true;
          console.log("認証エラー");
        });
    },
    // 以下、ページング時の処理
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
    // (( 遅延用の関数 ))
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
  },
  async created() {
    this.isLoading = true;
    await this.getUserAccount();
    await this.userCheckSessions();
    this.isLoading = false;
  },
};
</script>
<style lang="scss">
.list-leave-active {
  transition: all 0.6s;
}
.list-leave-to {
  transform: translateX(-100px);
  opacity: 0;
}
.list-move {
  transition: transform 1s;
}
/* 基本設定（背景やレイアウト） */
.container {
  display: flex;
  height: 50vh;
  justify-content: space-around;
  align-items: center;
}
</style>

