<template>

<nav>
    <ul class="sidebar" ref="sidebar">
      <li @click="hideSidebar">
        <a href="#"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            height="26"
            viewBox="0 -960 960 960"
            width="26"
            fill="#e3e3e3"
          >
            <path
              d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
            />
          </svg>
          ></a
        >
      </li>
      <li><a href="#" @click.prevent="$router.push('/adminHome')">Home</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminPrice')">Price</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminUser')">Users</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminControl')">Control Panel</a></li>
      <li><a href="#" @click.prevent="logout">Log Out</a></li>
    </ul>
    <ul>
      <li>
        <a href="#" @click.prevent="$router.push('/adminHome')"
          >Market Price Tracker-Admin</a
        >
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/adminHome')">Home</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/adminPrice')">Price</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/adminUser')">Users</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/adminControl')">Control Panel</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="logout">Log Out</a>
      </li>
      <li class="menu-btn" @click="showSidebar">
        <a href="#"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            height="26"
            viewBox="0 -960 960 960"
            width="26"
            fill="#e3e3e3"
          >
            <path
              d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"
            />
          </svg>
          ></a
        >
      </li>
    </ul>
  </nav>

<div class="control">
    <div class="controlPanel">
    <h1>Control panel</h1>

    <h3>Update Prices</h3>
    <button type="submit" class="btn" @click.prevent="fetchPices">Update</button>
  </div>
</div>
  

</template>


<script>
import { getToken, removeToken } from "@/utils/auth";

export default {
    name: 'adminControl',
    data(){
        return {
            urlappphp: process.env.VUE_APP_URLAPPPHP,
        }
    },

    methods: {

        async fetchPices(){
            try {
                const token = getToken();

                if (!token) {
                    console.error("No token found, redirecting to login.");
                    this.$router.replace("/login");
                    return;
                }

                const response = await fetch(this.urlappphp, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({ action: "fetchPrices" }),
                });

                const result = await response.json();
                console.log("Feedback response:", result);

                if (result.success) {
                    this.feedbackList = result.feedbacks;
                    this.populateFeedbackRatings();
                    this.calculateAverageRating();
                } else {
                    console.error("Failed to fetch feedbacks:", result.error);
                }
            } catch (error) {
                console.error("Error fetching feedbacks:", error);
            }
        },

        async logout() {
            try {
                removeToken();
                this.localUserData = {};
                this.$router.replace("/");
            } catch (error) {
                console.error("Logout error:", error);
            }
        },

        showSidebar() {
            this.$refs.sidebar.style.display = "flex";
        },

        hideSidebar() {
            this.$refs.sidebar.style.display = "none";
        },

        handleClickOutside(event) {
      if (this.$refs.sidebar &&this.$refs.sidebar.style.display === "flex") {
        const isClickInsideSidebar = this.$refs.sidebar.contains(event.target);
        const isClickOnMenuButton = event.target.closest(".menu-btn"); 

        if (!isClickInsideSidebar && !isClickOnMenuButton) {
          this.hideSidebar(); 
        }
      }
    },
    },

    mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
     
}
</script>

<style scoped>
/* Added navbar styles */
* {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  box-sizing: border-box;
}

html,
body {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav {
  background-color: white;
  box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);
  position: fixed;
  top: 0;
  z-index: 100;
  margin: 0;
  padding: 0;
  width: 100%;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav ul {
  width: 100%;
  list-style: none;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav li {
  height: 50px;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav a {
  height: 100%;
  padding: 0 30px;
  text-decoration: none;
  display: flex;
  align-items: center;
  color: black;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 500;
  font-size: 15px;
  letter-spacing: 0.5px;
}

nav li:first-child a {
  font-size: 18px;
  font-weight: 600;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav a:hover {
  background-color: #f0f0f0;
}

nav li:first-child {
  margin-right: auto;
}

.sidebar {
  position: fixed;
  top: 0;
  right: 0;
  height: 100vh;
  width: 250px;
  z-index: 999;
  background-color: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  box-shadow: -10px 0 10px rgba(0, 0, 0, 0.1);
  display: none;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar li {
  width: 100%;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar a {
  width: 100%;
  font-size: 16px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.menu-btn {
  display: none;
}

@media (max-width: 800px) {
  .hideMobile {
    display: none;
  }
  .menu-btn {
    display: block;
  }
}

@media (max-width: 400px) {
  .sidebar {
    width: 100%;
  }
}

.control {
  padding-top: 0;
  margin-top: -100px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
}

.controlPanel {
  max-width: 500px;
  margin: auto;
  color: #ffffff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding: 30px;
  text-align: center;
  background: linear-gradient(135deg, #2c2c2c, #333333);
  border-radius: 15px;  /* This creates the rounded corners */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.btn {
  margin-top: 10px;
  padding: 10px 20px;
  background: #ffd700;
  color: #000;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  float: right;
  transition: background 0.3s ease, transform 0.2s ease;
}

.btn:hover {
  background: #ffc107;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

</style>