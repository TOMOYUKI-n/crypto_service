<template>
  <div class="p-account__panel-wrap">
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
      <div class="p-account__followbtn">
        <button
          class="p-btn__follow"
          @click="followEvent(info.id_str)"
          :class="{ isFollowedColor: isFollowedFlg }"
        >
          <a v-if="isFollowedFlg">フォロー中</a>
          <a v-else>フォローする</a>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["value", "info"],
  data: () => {
    return {
      isFollowedFlg: false,
    };
  },
  methods: {
    delay(timeout) {
      return new Promise((resolve) => {
        setTimeout(resolve, timeout);
      });
    },
    followEvent(id) {
      this.$emit("followEvent");
      this.isFollowedFlg = !this.isFollowedFlg;
      console.log("btn押下");
    },
  },
};
</script>
