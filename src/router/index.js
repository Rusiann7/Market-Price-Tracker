import { createRouter, createWebHistory } from 'vue-router'
import { isAuthenticated } from '@/utils/auth'
import Landing from "@/Landing-Page.vue";
import Prices from "@/components/user/prices.vue";
import Feedback from "@/components/user/Feedback.vue";
import AdminHome from "@/components/admin/admin-home.vue";
import Compare from "@/components/user/compare.vue";
import Login from "@/components/user/login.vue";
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
    meta: { requiresAuth: true },
  },

  {
    path: "/adminPrice",
    name: "adminPrice",
    component: adminPrice,
    meta: { requiresAuth: true }
  },

  {
    path: "/adminUser",
    name: "adminUser",
    component: adminUser,
    meta: { requiresAuth: true }
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
    props: true
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


router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated()) {
      // Redirect to login page if not authenticated
      next({
        path: '/',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router;
