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
          </a>
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
          </a>
      </li>
    </ul>
  </nav>

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="control">
    <div class="controlPanel">
      <h1>Control panel</h1>
      <br>
      <h3>Update Prices</h3>
      <br>
      <button type="submit" class="btn" @click.prevent="fetchPrices">Update</button>
      <br>
      <p style="margin-top: 25px;"> <strong>Do not use too much</strong></p>
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
      async fetchPrices(){
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

            if (result.success) {
              alert("Prices updated successfully!");
            }else {
              console.error("Failed to fetch new prices:", result.error);
            }
        }catch (error) {
            console.error("Error fetching new prices:", error);
          }
      },

      async logout() {
        try {
          removeToken();
          this.localUserData = {};
          this.$router.replace("/");
        }catch (error) {
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
  background-color: #2d333f;
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
  color: #e3e3e3;
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
  background-color: #3a4252;
  color: white;
}

nav a:active {
  background-color: #4a5568;  /* Even lighter for pressed state */
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

.sidebar a:hover {
    background-color: #3a4252;
}

.menu-btn {
  display: none;
}

.menu-btn:hover {
  background-color: #3a4252;
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
  position: relative; 
  z-index: 2;
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
  background-color: #232831;
  /* background: linear-gradient(135deg, #2c2c2c, #333333); */
  border-radius: 15px;  /* This creates the rounded corners */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.btn {
  margin-top: 10px;
  padding: 10px 20px;
  background: #ffe082;
  color: #001821;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.btn:hover {
  background: #ffd448; /* Lighter yellow (#ffc107 → #ffe082) */
  color: #001821; /* Dark text on hover for contrast */
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.image-container {
  position: fixed; /* Changed from relative to fixed */
  width: 100%;
  height: 100vh;
  top: 0;
  left: 0;
  z-index: 1;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: blur(5px);
  position: absolute;
  top: 0;
  left: 0;
}

.img-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(45, 51, 63, 0.452);
}
</style>