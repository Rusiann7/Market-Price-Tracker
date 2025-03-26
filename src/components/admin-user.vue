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
      <li>
        <a href="#" @click.prevent="$router.push('/adminPrice')">Price</a>
      </li>
      <li><a href="#" @click.prevent="$router.push('/adminUser')">Users</a></li>
      <li>
        <a href="#" @click.prevent="$router.push('/adminUser')">Log Out</a>
      </li>
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
        <span> I agree to the terms & conditions </span>

        <br />

        <button type="submit" class="btn" value="POST">Sign up</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "adminUser",
  data() {
    return {
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
        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
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
        localStorage.removeItem("token");
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
  },

  mounted() {
    // Clear the form data when the component is mounted
    this.FormData = {
      email: "",
      initpassword: "",
      conpassword: "",
    };
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

.signup {
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
  background: linear-gradient(135deg, #2c2c2c, #333333);
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
  margin-top: 20px;
  padding: 10px 20px;
  background: #ffd700;
  color: #000;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.btn:hover {
  background: #ffc107;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

@media (max-width: 600px) {
  .button-container {
    flex-direction: column;
  }
}

.top-text {
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 100px;
}

.check-box {
  margin-top: 15px;
  margin-bottom: 15px; /* Add space below the checkbox */
}

.note {
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 50px;
}
</style>
