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
  watch: {
  },
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
      this.meetingId = "78589450816";
  }
};
</script>

<style>
.container-zoom {
  width: 70%;
  height: 100%;
}
</style>
