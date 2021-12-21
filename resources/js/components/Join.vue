<template>
  <div class="join">
    <div class="join-card">
      <div class="join-header">
        <h2>Join Meeting</h2>
      </div>
        <form action="{{}}">
            <div>
                <div class="form-item">
                    <label for="alias">Alias: </label>
                    <input  type="text" name="nickname">
                </div>

                <div class="form-item">
                    <label for="meetingId">Meeting ID: </label>
                    <input  type="text" name="meetingId">
                </div>

                <div class="form-item">
                    <button>Join Meeting</button>
                </div>
            </div>
        </form>

    </div>
  </div>
</template>

<script>
export default {
  name: "join",
  data: function() {
    return {

    };
  }
};
</script>

<style>
.join {
  padding: 5px;
}

.join-card {
  margin-top: 50px;
  margin-left: auto;
  margin-right: auto;
  padding: 20px;
  width: 30%;
  height: 50%;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12), 0 0 6px rgba(0, 0, 0, 0.04);
}

.join-header {
  border-bottom: 1px solid #d7dae2;
  padding: 20px;
  margin-bottom: 20px;
}

.form-item {
  display: block;
  padding: 5px;
}

</style>
