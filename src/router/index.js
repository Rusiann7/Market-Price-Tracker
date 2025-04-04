import { createRouter, createWebHistory } from "vue-router";
import Landing from "@/Landing-Page.vue";
import Prices from "@/components/prices.vue";
import Feedback from "@/components/Feedback.vue";
import AdminHome from "@/components/admin/admin-home.vue";
import Compare from "@/components/compare.vue";
import Login from "@/components/login.vue";
import adminPrice from "@/components/admin/admin-price.vue";
import adminUser from "@/components/admin/admin-user.vue";
import adminReset from "@/components/admin/admin-reset.vue";
import App from "@/App.vue";


const routes = [
  {
    path: "/",
    name: "LandingPage",
    component: Landing,
    meta: { title: "Home Page" },
  },

  {
    path: "/price",
    name: "PricePage",
    component: Prices,
    meta: { title: "Prices" },
  },

  {
    path: "/feedback",
    name: "FeedBack",
    component: Feedback,
    meta: { title: "Feedback" },
  },

  {
    path: "/adminHome",
    name: "adminDashboard",
    component: AdminHome,
    meta: { title: "Admin" },
  },

  {
    path: "/adminPrice",
    name: "adminPrice",
    component: adminPrice,
    meta: {title: "AdminPrice"}
  },

  {
    path: "/adminUser",
    name: "adminUser",
    component: adminUser,
    meta: {title: "AdminUser"}
  },

  {
    path: "/compare",
    name: "comPare",
    component: Compare,
    meta: { title: "Compare" },
  },

  {
    path: "/adminReset",
    name: "adminReset",
    component: adminReset,
    meta: { title: "Reset" },
  },

  {
    path: "/password",
    name: "Password",
    component: adminReset,
    meta: { title: "Password" },
  },

  {
    path: "/code",
    name: "Code",
    component: adminReset,
    meta: { title: "Code" },
  },

  {
    path: "/login",
    name: "logIn",
    component: Login,
    meta: {title: "Login"}
  },

  {
    path: "/app",
    name: "App",
    component: App,
  }
  
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
