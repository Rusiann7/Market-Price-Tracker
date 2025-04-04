<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>

<style>
/* Global reset - add this to App.vue or main.css */
html,
body,
#app {
  margin: 0 !important;
  padding: 0 !important;
}

body {
  overflow-x: hidden;
}
</style>