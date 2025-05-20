<template>

  <div v-if="isLoading" class="loading-screen">
    <div class="loading-spinner"></div>
    <p>Loading...</p>
  </div>

  <div v-show="!isLoading"> 

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
      urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
      isLoading: false
    };
  },

  methods: {
    async loginusr() {
      this.isLoading = true;
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
          this.$router.replace("/adminHome");
        } else {
          this.responseMessage = result.error || alert("Logged in failed.");
          alert("Incorrect email or password.");
        }
      } catch (error) {
        console.error("Error:", error);
        this.responseMessage = "Failed to communicate with the server.";
      } finally {
      this.isLoading = false;
    }
    },
  },
};
</script>

<style scoped>
.login{
  position: relative; /* above overlay */
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
  background: #ffd448; 
  color: #001821; 
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

@media (max-width: 600px) {
  .button-container {
    flex-direction: column;
  }
}

.top-text{
  position: relative; /* above overlay */
  z-index: 2;
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 75px;
  color: #ffffff;
}


.forgot-password {
  display: block;
  text-align: center;
  color: white; 
  text-decoration: none; 
  font-size: 14px;
  margin-top: 30px;
  transition: color 0.3s ease;
}

.forgot-password:hover {
  color: #808080; 
}

.input-field {
  margin-bottom: 15px; /* space below input field */
  margin-top: 15px;
  padding: 12px 15px;
  width: 100%;
  font-size: 16px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  color: white;
}

.loading-spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #ffffff;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 10px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.image-container {
  position: fixed; 
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
