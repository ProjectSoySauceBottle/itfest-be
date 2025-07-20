import "./bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import { createApp } from "vue";
import HomePage from './components/HomePage.vue';
import App from "./components/App.vue";

const app = createApp(App);
app.mount("#app");
createApp(HomePage).mount('#app');
