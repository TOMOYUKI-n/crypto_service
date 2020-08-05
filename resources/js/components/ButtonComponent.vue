<template>
  <div class="p-account__followbtn">
    <button
      class="p-btn__follow"
      @click="followEvent()"
      :class="{ isFollowedColor: isFollowedFlg }"
    >
      <a v-if="isFollowedFlg">
        フォロー中
      </a>
      <a v-else>
        フォローする
      </a>
    </button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["value"],
  data: () => {
    return {
      isFollowedFlg: false,
    };
  },
  methods: {
    followEvent() {
      this.$emit("followEvent");
      this.isFollowedFlg = !this.isFollowedFlg;
    },
  },
};
</script>

