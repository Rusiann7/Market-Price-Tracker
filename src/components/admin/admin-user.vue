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
      <li><a href="#" @click.prevent="$router.push('/adminHome')">Home</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminPrice')">Price</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminUser')">Users</a></li>
      <li><a href="#" @click.prevent="$router.push('/adminControl')">Control Panel</a></li>
      <li><a href="#" @click.prevent="logout">Log Out</a></li>
    </ul>

    <ul>
      <li>
        <a href="#" @click.prevent="$router.push('/adminHome')"
          >Market Price Tracker-Admin</a>
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

  <div v-if="isLoading" class="loading-screen">
    <div class="loading-spinner"></div>
    <p>Loading...</p>
  </div>

  <div v-show="!isLoading"> 
  
  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div v-if="$route.path === '/createUser'">

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
        <br/>

        <p>Password:</p>

        <input
          type="password"
          class="input-field"
          placeholder="Create Password"
          v-model="FormData.initpassword"
          required
        />
        <br/>

        <p>Confirm Password:</p>

        <input
          type="password"
          class="input-field"
          placeholder="Confirm Password"
          v-model="FormData.conpassword"
          required
        />
        <br/>

        <input type="checkbox" class="check-box" />
        <span> I agree to the <a :href="$router.resolve('/termsandcondition').href" target="_blank" rel="noopener noreferrer" style="color: white">Terms and Conditions</a> </span>
        <br/>

        <button type="submit" class="btn" value="POST">Sign up</button>
      </form>
    </div>
  </div>
  </div>

  <div v-if="$route.path === '/adminUser'">

  <div class="top-text">
    <h1>Users</h1>
  </div>

  <div class="rating-reviews" id="reviews">
    <div class="ratings-reviews-container">
      <!-- left side) -->
       <div class="overall-rating">
          <p>Choose what will you do</p>

        <div class="button-container">
            
          <button class="btn" @click.prevent="$router.push('/createUser')">
            Add Users
          </button>

          <button class="btn" @click.prevent="$router.push('/deleteUser')">
            Remove Users
          </button>

        </div>
       </div>
      
      <!-- right side) -->
      <div class="feedback-container">
        <h2 class="section-title">Users & Date</h2>
        <div class="feedback-list">
          <div
            v-for="(user, index) in userList"
            :key="index"
            class="feedback-item"
            >
            <!--user rating -->
            <strong class="username">{{ user.email }}</strong>
            <p class="comment">{{ user.created }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <div v-if="$route.path === '/deleteUser'" class="main-content">
    <button class="btn" @click.prevent="$router.push('/adminUser')"> 
        <svg xmlns="http://www.w3.org/2000/svg" 
          height="24px" viewBox="0 -960 960 960" 
          width="24px" fill="#000000">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg> 
      </button>
    <div class="addItem">
      <div class="form-box">

        <form @submit.prevent="delUsers">
          <br>
          <p>Delete User:</p>
          <br>

          <select v-model="selectedItem" class="btn" required>
            <option value="" disabled>-- Select a user --</option>
            <option v-for="user in userList" :key="user.email" :value="user.email">
              {{ user.email }}
            </option>
          </select>
          <br>

          <button type="submit" class="btn">Delete</button>
        </form>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import { removeToken, getToken} from '@/utils/auth';

export default {
  name: "adminUser",
  data() {
    return {
      selectedItem: '',
      userList: [],
      localUserData: {},
      FormData: JSON.parse(localStorage.getItem("userData")) || {
        email: "",
        initpassword: "",
        conpassword: "",
      },
      responseMessage: null,
      urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
      isLoading: true
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
          this.$router.push('/adminUser');
        } else {
          alert("Password is not the same");
        }
      } catch (error) {
        console.error("Error:", error);
        this.responseMessage = "Failed to communicate with the server.";
      } finally {
        this.isLoading = false;
      }
    },

    async fetchUsers() {
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
          body: JSON.stringify({ action: "getUsers" }),
        });

        const result = await response.json();
        console.log("Feedback response:", result);

        if (result.success) {
          this.userList = result.users;
        }else {
          console.error("Failed to fetch users:", result.error);
        }
      }catch (error) {
        console.error("Error fetching users:", error);
      } finally {
        this.isLoading = false;
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

    async delUsers(){
      this.isLoading = true;

      if (!this.selectedItem) {
          alert("Please select an item.");
          return;
        }

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
          body: JSON.stringify({ action: "deleteUsers", 
          User: this.selectedItem, }),    
        });

        const result = await response.json();

        if (result.success) {
          this.selectedItem = null;
          alert("Successfully deleted user.");
          this.$router.push('/adminUser'); // go back to the price list
        } else {
          console.error("Failed to delete user:", result.error);
        }
      } catch (error) {
        console.error("Error deleting users:", error);
      } finally {
        this.isLoading = false;
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
    this.fetchUsers();
    this.priceInterval = setInterval(() => {
      this.fetchUsers();
    }, 10000);
    // clear form data when mounted
    this.FormData = {
      email: "",
      initpassword: "",
      conpassword: "",
    };
    document.addEventListener("click", this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
    clearInterval(this.priceInterval);
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

.signup {
  position: relative; /* above overlay */
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
  margin-bottom: 15px; /* space below each input field */
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

.top-text {
  position: relative; /* above overlay */
  z-index: 2;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 100px;
}

.check-box {
  margin-top: 15px;
  margin-bottom: 15px; /* space below the checkbox */
}

.note {
  position: relative; /* above overlay */
  z-index: 2;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 50px;
  margin: 15px;
}

.main {
  position: relative; /* above overlay */
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

.ratings-reviews {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #2c2c2c, #333333);
  color: white;
  padding: 40px;
  padding-top: 80px;
  border-radius: 12px;
  max-width: 100%;
  min-height: 100vh;
  margin: 0 auto;
  margin-top: 120px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); 
}

/* containers */
.ratings-reviews-container {
  position: relative; 
  z-index: 2;
  display: flex;
  justify-content: space-between; 
  align-items: flex-start; 
  width: 90%; 
  max-width: 1200px; 
  margin: 20px auto; 
  gap: 40px;
}

/* overall styling */
.overall-rating,
.feedback-container {
  background-color: #232831;
  color: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Left */
.overall-rating {
  width: 30%; 
  height: auto;
  position: sticky; 
  top: 215px;
}

/* p */
.overall-rating p {
  font-size: 2.5rem;
  font-weight: bold;
  margin-top: 20px;
  text-align: center; 
  color: white; 
}

/* right side */
.feedback-container {
  width: 65%; 
  background-color: #232831;
  color: #ffffff;
  max-height: 70vh; 
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  margin-top: 45px;
  scrollbar-width: thin;
  scrollbar-color: #4a5568 #2d3748;
}


.feedback-list {
  max-height: 80%;
  overflow-y: auto;
  margin-top: 20px;
}

/* febback item */
.feedback-item {
  border-top: 2px solid #ddd;
  padding: 15px;
  transition: background-color 0.3s ease;
}

.feedback-item:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  background-color: #4a5568;
}

.feedback-container h2 {
  margin-bottom: 25px;
  font-size: 1.5rem;
  font-weight: 600;
  color: #ffffff;
  text-align: center;
  position: relative;
  padding-bottom: 10px;
}

/* scrollbar */
.feedback-container::-webkit-scrollbar {
  width: 8px;
}

.feedback-container::-webkit-scrollbar-thumb {
  background-color: #444;
  border-radius: 4px;
}

.feedback-container::-webkit-scrollbar-track {
  background-color: #2c2c2c;
}

@media (max-width: 1024px) {
  .ratings-reviews-container {
    flex-direction: column; /* vertically */
    align-items: center;
    gap: 20px;
  }

  .overall-rating,
  .feedback-container {
    width: 90%; 
    max-width: 600px;
  }

  .overall-rating {
    position: relative; 
    top: 0;
  }
}

@media (max-width: 768px) {
  .ratings-reviews-container {
    gap: 15px;
  }

  .overall-rating,
  .feedback-container {
    width: 100%; 
  }

  .overall-rating h3 {
    font-size: 18px;
  }

  .feedback-container {
    max-height: 350px;
  }

  .feedback-item .user-info {
    font-size: 14px;
  }

  .feedback-item .comment {
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .overall-rating h3 {
    font-size: 16px;
  }

  .feedback-item .user-info {
    font-size: 12px;
  }

  .feedback-item .comment {
    font-size: 12px;
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

.addItem {
  position: relative; /* above overlay */
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 20px;
  margin: 10px ;
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

.form-box {
  width: 100%;
}

.button-container {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding: 20px;
}

.main-content {
  position: relative; /* above overlay */
  z-index: 2;
  padding-top: 0;
  margin-top: -180px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 15px;
  padding-right: 15px;
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
