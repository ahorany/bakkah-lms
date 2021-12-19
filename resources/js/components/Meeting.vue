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
      this.meetingId = "78589450816";
=======
      // this.nickname = "khaled";
      // this.meetingId = "74816836253";
>>>>>>> a1c612ffca17cbce6b3efded6c9261dd7a56636f
  }
};
</script>

<style>
.container-zoom {
  width: 70%;
  height: 100%;
}
</style>
