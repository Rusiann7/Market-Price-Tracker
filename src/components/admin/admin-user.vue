<template>
  <!-- Added Navigation Bar -->
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
  
  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="top-text">
    <h1>Sign Up</h1>
  </div>

  <div class="note">
    <p>
      <strong>Note:</strong> Only add new admin users when needed and ensure you
      trust the soon to be bearer of the account.
    </p>
  </div>

  <div class="signup">
    <div class="form-box">
      <form
        id="signup"
        class="input-group"
        method="post"
        @submit.prevent="submitForm"
      >
        <p>Email:</p>

        <input
          type="email"
          class="input-field"
          placeholder="Email"
          v-model="FormData.email"
          required
        />

        <br />

        <p>Password:</p>

        <input
          type="password"
          class="input-field"
          placeholder="Create Password"
          v-model="FormData.initpassword"
          required
        />

        <br />

        <p>Confirm Password:</p>

        <input
          type="password"
          class="input-field"
          placeholder="Confirm Password"
          v-model="FormData.conpassword"
          required
        />

        <br />

        <input type="checkbox" class="check-box" />
        <span> I agree to the <a :href="$router.resolve('/termsandcondition').href" target="_blank" rel="noopener noreferrer" style="color: white">Terms and Conditions</a> </span>

        <br />

        <button type="submit" class="btn" value="POST">Sign up</button>
      </form>
    </div>
  </div>
</template>

<script>
import { removeToken, getToken} from '@/utils/auth';

export default {
  name: "adminUser",
  data() {
    return {
      localUserData: {},
      FormData: JSON.parse(localStorage.getItem("userData")) || {
        email: "",
        initpassword: "",
        conpassword: "",
      },
      responseMessage: null,
      urlappphp: process.env.VUE_APP_URLAPPPHP,
    };
  },

  methods: {
    async submitForm() {
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
          body: JSON.stringify({ ...this.FormData, action: "signup" }),
        });

        const result = await response.json();
        if (result.success) {
          this.responseMessage = result.message || "Success!";
          alert("Account created successfully!");
          this.FormData = { email: "", initpassword: "", conpassword: "" };
        } else {
          alert("Password is not the same");
        }
      } catch (error) {
        console.error("Error:", error);
        this.responseMessage = "Failed to communicate with the server.";
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

    handleClickOutside(event) {
        if (this.$refs.sidebar &&this.$refs.sidebar.style.display === "flex") {
          const isClickInsideSidebar = this.$refs.sidebar.contains(event.target);
          const isClickOnMenuButton = event.target.closest(".menu-btn"); 

          if (!isClickInsideSidebar && !isClickOnMenuButton) {
            this.hideSidebar(); 
          }
        }
      },

    showSidebar() {
      this.$refs.sidebar.style.display = "flex";
    },

    hideSidebar() {
      this.$refs.sidebar.style.display = "none";
    },
  },

  mounted() {
    // Clear the form data when the component is mounted
    this.FormData = {
      email: "",
      initpassword: "",
      conpassword: "",
    };
    document.addEventListener("click", this.handleClickOutside);
  },

    beforeUnmount() {
      document.removeEventListener("click", this.handleClickOutside);
    },
};
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

.signup {
  position: relative; /* Make sure content appears above overlay */
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 25px;
  margin: 0 auto;
  max-width: 450px;
  width: 90%;
  color: white;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  /* background: linear-gradient(135deg, #2c2c2c, #333333); */
  background-color: #232831;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  min-height: auto;
  max-height: 450px;
  align-self: center;
  margin-top: 100px;
  margin-bottom: 0;
  font-size: 17px;
}

.input-field {
  margin-bottom: 15px; /* Add space below each input field */
  margin-top: 15px;
  padding: 12px 15px;
  width: 100%;
  font-size: 16px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
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

@media (max-width: 600px) {
  .button-container {
    flex-direction: column;
  }
}

.top-text {
  position: relative; /* Make sure content appears above overlay */
  z-index: 2;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 100px;
}

.check-box {
  margin-top: 15px;
  margin-bottom: 15px; /* Add space below the checkbox */
}

.note {
  position: relative; /* Make sure content appears above overlay */
  z-index: 2;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 50px;
  margin: 15px;
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
