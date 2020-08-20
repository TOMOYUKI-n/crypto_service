<template>
  <div class="l-main__container">
    <div id="login" class="p-login__container">
      <div>
        <!--入力エリア-->
        <div class="p-login__inputError" v-if="errors.email">{{ message }}</div>
        <div class="p-login__inputError" v-if="errors.password">{{ message }}</div>

        <div class="p-login__inner">
          <i class="fas fa-user"></i>
          <input type="text" class="p-login__form" v-model="email" placeholder="メールアドレス" />
        </div>

        <div class="p-login__inner">
          <i class="fas fa-unlock-alt"></i>
          <input type="password" class="p-login__form" v-model="password" placeholder="パスワード" />
        </div>

        <!--ボタンエリア-->
        <div class="p-top__login">
          <button @click="login" type="submit" class="p-login__text p-btn__login">ログイン</button>
        </div>

        <p class="p-login__text p-login-add-margin__3" style="font-size: 13px;">アカウントをお持ちで無い方はこちらから</p>
        <div class="p-top__login">
          <a class="p-login__text p-btn__login" @click="twitterLogin">Twitterでログイン</a>
        </div>
        <div class="p-top__login" @click="register">
          <a class="p-login__text p-btn__new">新規登録</a>
        </div>

        <div class="p-top__login">
          <a class="p-login__forgot" @click="passlost">パスワードを忘れた方はこちら</a>
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
      email: "",
      password: "",
      errors: {},
      message: "",
    };
  },
  methods: {
    login() {
      // データの保存
      this.saveLoginData();
      // 送信
      const url = "/login";
      const params = {
        email: this.email,
        password: this.password,
      };
      axios
        .post(url, params)
        .then((response) => {
          console.log("OK");
          location.href = "/trend";
        })
        .catch((error) => {
          console.log("NG");
          const responseErrors = error.response.data.errors;
          const errorMessage = error.response.data;
          this.exchange(errorMessage.message);
        
          const errorsData = {};
          for (let key in responseErrors) {
            errorsData[key] = responseErrors[key][0];
          }
          console.log(errorsData);
          this.errors = errorsData;
        });
    },
    register() {
      location.href = "/register";
    },
    twitterLogin() {
      location.href = "/auth/login/twitter";
    },
    passlost() {
      location.href = "/password/reset";
    },
    saveLoginData() {
      // データの保存
      const loginArray = { email: this.email, password: this.password };
      const loginData = JSON.stringify(loginArray);
      localStorage.setItem("loginData", loginData);
      console.log("save storage");
    },
    getLoginData() {
      const loginData = localStorage.getItem("loginData");
      const loginArray = JSON.parse(loginData);
      this.email = loginArray.email;
      this.password = loginArray.password;
      console.log("get data");
    },
    exchange(message) {
        if(message == "The given data was invalid."){
            this.message = "メールアドレスまたはパスワードを正しく入力してください";
        }else if(message == "validation.required"){
            this.message = "入力してください";
        // }else{
        //     this.message = "メールアドレスまたはパスワードが正しくありません.";
        }
    }
  },
  mounted() {
    this.getLoginData();
    console.log("mouted");
  },
};
</script>
