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
  components: {
    ZoomFrame,
  },

   props: ['nickname','meetingId'],

  created : function (){
<<<<<<< HEAD
      this.nickname = "khaled";
      this.meetingId = "76113869594";
=======
      // this.nickname = "khaled";
      // this.meetingId = "74816836253";
>>>>>>> 53edfbdb3ffadebc1ec002039de0744581d3add7
  }
};
</script>

<style>
.container-zoom {
  width: 70%;
  height: 100%;
}
</style>
