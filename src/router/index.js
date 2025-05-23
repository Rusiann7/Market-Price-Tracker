import { createRouter, createWebHistory } from 'vue-router'
import { isAuthenticated } from '@/utils/auth'
import Landing from "@/Landing-Page.vue";
import Prices from "@/components/user/prices.vue";
import Feedback from "@/components/user/Feedback.vue";
import AdminHome from "@/components/admin/admin-home.vue";
import Compare from "@/components/user/compare.vue";
import Login from "@/components/login.vue";
import adminPrice from "@/components/admin/admin-price.vue";
import adminUser from "@/components/admin/admin-user.vue";
import adminReset from "@/components/admin/admin-reset.vue";
import adminCtrl from '@/components/admin/admin-ctrl.vue';
import termsAndConditions from '@/components/terms-and-conditions.vue';
import addedAdmin from '@/components/added-admin.vue';
import newsandupdates from '@/components/user/news.vue';
import App from "@/App.vue";
import yes from "@/Hello_World.vue";

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
    meta: { requiresAuth: true, title: "Admin Home" },
  },

  {
    path: "/adminPrice",
    name: "adminPrice",
    component: adminPrice,
    meta: { requiresAuth: true, title: "Admin Price" }
  },

  {
    path: "/addItem",
    name: "addItem",
    component: adminPrice,
    meta: { requiresAuth: true, title: "Add" }
  },

  {
    path: "/editItem",
    name: "editItem",
    component: adminPrice,
    meta: { requiresAuth: true, title: "Edit" }
  },

  {
    path: "/deleteItem",
    name: "deleteItem",
    component: adminPrice,
    meta: { requiresAuth: true, title: "Delete" }
  },

  {
    path: "/adminUser",
    name: "adminUser",
    component: adminUser,
    meta: { requiresAuth: true, title: "Admin User" }
  },

  {
    path: "/createUser",
    name: "createUser",
    component: adminUser,
    meta: { requiresAuth: true, title: "Admin User" }
  },

  {
    path: "/deleteUser",
    name: "deleteUser",
    component: adminUser,
    meta: { requiresAuth: true, title: "Admin User" }
  },

  {
    path: "/adminControl",
    name: "adminControl",
    component: adminCtrl,
    meta: { requiresAuth: true, title: "Admin Control" }
  },

  {
    path: "/adminReset",
    name: "adminReset",
    component: adminReset,
    meta: { title: "Reset"},
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
    path: "/compare",
    name: "comPare",
    component: Compare,
    meta: { title: "Compare" },
  },

  {
    path: "/login",
    name: "logIn",
    component: Login,
    meta: {title: "Login"}
  },

  {
    path: "/termsandcondition",
    name: "endUser",
    component: termsAndConditions,
    meta: {title: "Terms and Condition"}
  },

  {
    path: "/addedadmin",
    name: "addAdmin",
    component: addedAdmin,
    meta: {title: "Added Admin"}
  },

  {
    path: "/newsandupdates",
    name: "NewsUpdates",
    component: newsandupdates,
    meta: {title: "News and Updates"}
  },

  {
    path: "/yessssssssssssssssssssssssssssssssssssss",
    name: "yesSirr",
    component: yes,
    meta: {title: "Yes"}
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

  if (to.meta?.title) {
    document.title = to.meta.title
  }
})

export default router;
