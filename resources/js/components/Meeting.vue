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
  components: {
    ZoomFrame,
  },

   props: ['nickname','meetingId'],

  created : function (){
      // this.nickname = "khaled";
      // this.meetingId = "74816836253";
  }
};
</script>

<style>
.container-zoom {
  width: 70%;
  height: 100%;
}
</style>
