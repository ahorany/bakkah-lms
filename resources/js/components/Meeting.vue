<template>
  <div class="meeting">
    <div class="container-zoom">
      <ZoomFrame :nickname="nickname" :meetingId="meetingId"/>
    </div>
  </div>
</template>

<script>
import ZoomFrame from "./ZoomFrame.vue";

export default {
  name: "app",
  data: function () {
      return {
          nickname: '',
          meetingId: '',
      }
  },
  components: {
    ZoomFrame,
  },
  created : function (){
      this.nickname = "khaled";
      this.meetingId = "76113869594";
  }
};
</script>

<style>
.container-zoom {
  width: 70%;
  height: 100%;
}
</style>
