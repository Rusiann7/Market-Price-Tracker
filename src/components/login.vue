<template>

<div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="top-text">
    <h1>Log In</h1>
  </div>

  <div class="login">
    <div class="form-box">
      <form
        id="login"
        class="input-group"
        method="get"
        @submit.prevent="loginusr"
      >
        <p>Email: </p>

        <input
          type="email"
          class="input-field"
          placeholder="Email"
          v-model="FormDatal.email"
          required
        />
        <p>Password: </p>

        <input
          type="password"
          class="input-field"
          placeholder="Password"
          v-model="FormDatal.password"
        />

        <br>

        <a
          href="#"
          class="forgot-password"
          @click.prevent="$router.push('/adminReset')"
          >Forget Password</a
        >

        <br>

        <button type="submit" class="btn" value="GET">Log In</button>
      </form>
    </div>
  </div>
</template>

<script>
import { setToken } from "@/utils/auth";

export default {
  name: "logIn",
  data() {
    return {
      FormData: JSON.parse(localStorage.getItem("userData")) || {
        email: "",
        initpassword: "",
        conpassword: "",
      },
      FormDatal: {
        email: "",
        password: "",
      },
      responseMessage: null,
      urlappphp: process.env.VUE_APP_URLAPPPHP,
    };
  },

  methods: {
    async loginusr() {
      try {
        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ ...this.FormDatal, action: "login" }),
        });
        const result = await response.json();

        if (result.success) {
          if (result.token) {
            setToken(result.token);
          }
          this.FormData = {
            ...this.FormData,
            ...result.userData,
          };

          localStorage.setItem("userData", JSON.stringify(this.FormData));

          this.FormData = { email: "", initpassword: "", conpassword: "" };

          await this.$router.replace("/adminHome");
        } else {
          this.responseMessage = result.error || "Login failed";
        }
      } catch (error) {
        console.error("Error:", error);
        this.responseMessage = "Failed to communicate with the server.";
      }
    },
  },
};
</script>

<style scoped>
.login{
  position: relative; /* Make sure content appears above overlay */
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 20px;
  margin: 0 auto;
  max-width: 400px;
  width: 90%;
  color: white;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  /*background: linear-gradient(135deg, #2c2c2c, #333333);*/
  background-color: #232831;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  min-height: auto;
  max-height: 475px;
  align-self: center;
  margin-top: 125px;
  margin-bottom: 0;
  font-size: 17px;
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

.top-text{
  position: relative; /* Make sure content appears above overlay */
  z-index: 2;
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 75px;
  color: #ffffff;
}


.forgot-password {
  display: block;
  text-align: center;
  color: white; /* Gray color */
  text-decoration: none; /* Remove underline */
  font-size: 14px;
  margin-top: 30px;
  transition: color 0.3s ease;
}

.forgot-password:hover {
  color: #808080; /* Change to black on hover */
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
