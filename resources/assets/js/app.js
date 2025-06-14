import {createApp} from 'vue';
import './bootstrap.js'
import App from "./App.vue";

const app = createApp(App);

// Register Vue component globally
// app.component('example', Example);

// Mount Vue
app.mount("#vueApp");

app.config.globalProperties.$appComponent = 'example';  // Set default component

console.log("✅ Vue app mounted!");
