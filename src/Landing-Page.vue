<template>
  <!-- Added Navigation Bar -->
  <nav>
    <ul class="sidebar" ref="sidebar">
      <li @click="hideSidebar">
        <a href="#">
          <svg
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
      <li><a href="#" @click.prevent="$router.push('/')">Home</a></li>
      <li><a href="#" @click.prevent="$router.push('/price')">Price</a></li>
      <li><a href="#" @click.prevent="$router.push('/compare')">Compare</a></li>
      <li><a href="#" @click.prevent="$router.push('/newsandupdates')">News and Updates</a></li>
      <li><a href="#" @click.prevent="$router.push('/feedback')">Feedback</a></li>
    </ul>

    <ul>
      <li>
        <a href="#" @click.prevent="$router.push('/')">Market Price Tracker</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/')">Home</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/price')">Price</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/compare')">Compare</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/newsandupdates')">News and Update</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/feedback')">Feedback</a>
      </li>
      <li class="menu-btn" @click="showSidebar">
        <a href="#">
          <svg
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

  <div v-if="captcha" key="captcha" class="loading-screen">
    <h1>Verify You're Human</h1>
    <p>Complete the CAPTCHA to continue.</p>
    <div class="cf-turnstile" data-sitekey="0x4AAAAAABeBd5qJe7k3qqQy" data-callback="onSuccess" data-theme="dark"></div>
  </div>


  <div v-show="!captcha"> 

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="home-content" id="Home">
    <h1>Welcome!</h1>
    <p>Choose what will you do</p>
    <div class="button-container">
      <button class="btn" @click.prevent="$router.push('/price')">
        To Prices
      </button>
      <button class="btn" @click.prevent="$router.push('/login')">
        To Admin
      </button>
    </div>
    <p style="font-size: smaller; padding-top: 50px;">By continuing you are agreeing to the <a href="#" style="color: white;" @click.prevent="handleTermsClick">Terms and Conditions</a></p>
  </div>
     </div>   
</template>

<script>
export default {
  name: "LandingPage",
  data(){
    return {
      captcha: true,
      urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
    }
  },
  methods: {
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

    handleTermsClick(event) {
      if (event.shiftKey && event.ctrlKey && event.altKey) {
        const termsUrl = this.$router.resolve('/yessssssssssssssssssssssssssssssssssssss').href;
        window.open(termsUrl, '_blank', 'noopener,noreferrer');
      } else {
        const termsUrl = this.$router.resolve('/termsandcondition').href;
        window.open(termsUrl, '_blank', 'noopener,noreferrer');
      }
    },

    async captchaVerify() {
      try {
        if (document.cookie.includes("cf_verified=1")) {
          this.captcha = false;
        }
      } catch (error) {
        console.error('Error verifying captcha:', error);
      }
    },
  },

  mounted() {
    window.onSuccess = async () => {
      document.cookie = "cf_verified=1; path=/; max-age=86400";
      this.captcha = false;
    };

    this.captchaVerify();

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
  background-color: #4a5568;  /* pressed state */
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

.home-content {
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
  color: #ffffff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: #232831;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  min-height: auto;
  max-height: 350px;
  align-self: center;
  margin-top: 100px;
  margin-bottom: 0;
}

#Home {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 20px;
  margin-top: 250px;
  box-sizing: border-box;
}

.home-content h1,
.home-content p {
  color: white;
  z-index: 1;
}

.home-content h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  margin-top: 1.5rem;
}

.home-content p {
  font-size: 1.3rem;
  margin-bottom: 2rem;
}

.button-container {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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