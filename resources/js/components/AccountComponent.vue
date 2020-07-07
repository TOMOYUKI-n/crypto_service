<template>
<div class="l-main-container">
    <div class="c-main__title">{{ ('Trend Account Index') }}</div>

    <div class="c-main__function-head">
        <div class="p-account__text">twitterで「仮想通貨」というキーワードを
            ユーザ名またはプロフィールに記載しているユーザを一覧で表示します。(1日1回更新)
        </div>
        
        <div class="p-account__flex">
            <input type="checkbox">自動フォロー
            <button class="p-btn p-btn__follow">実行</button>
        </div>
        
        <div class="p-account__text p-account__text__attend">
            ※一覧に表示されているアカウントを全て自動でフォローしていきます
        </div>
    </div>

    <div class="p-account__panel-wrap" v-for="info in userInfoData" v-bind:key="info.index">
        <div class="p-account__inner">
            <div class="p-account__section-top">
                <div class="p-account__inner2">
                    <div class="p-account__name">
                        {{ info.name }}
                    </div>    
                    <div class="p-account__username">
                        {{ info.screen_name }}
                    </div>
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
                <div class="p-account__prof">
                    {{ info.description }}
                </div>
            </div>
            <div class="p-account__section-bottom">
                <div class="p-account__tweet">
                    {{ info.status.text }}
                </div>
            </div>
            <button class="p-btn p-btn__follow">
                    <a href="#">フォローする</a>
            </button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props:[ 'userinfo' ],
    name:'userinfo',
    data: function() {
        return {
            userInfoData: this.userinfo,//propsを直接操作するのはNGなので、データとして保持
            errored:false,
            cheched:false,
        }
    },
    methods: {
        followed() {
            if(this.cheched === true){
                // 選択したvalue値をkeywordとして送信
                axios.get(`/trend/search?q=${keyword}`)
                .then(res => {
                    this.trendData = this.res.data;
                    this.keyword = this.res.keyword;
                })
                .catch(error => {
                    this.errored = true;
                })
            }
        },
    },
}
</script>
