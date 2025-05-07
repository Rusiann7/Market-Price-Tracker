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
            fill="#e3e3e3">
            <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/>
          </svg>
        </a>
      </li>
    </ul>
  </nav>

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="top-text">
    <h1>Feedbacks</h1>
  </div>

  <div class="rating-reviews" id="reviews">
    <div class="ratings-reviews-container">
      <!-- left side) -->
      <div class="overall-rating">
        <h3>Overall Rating</h3>
        <div class="stars">
          <span
            v-for="star in 5"
            :key="star"
            :class="{ filled: star <= roundedRating }"
            >★
          </span>
        </div>
        <p>{{ averageRating }}/5</p>
      </div>

      <!-- right side) -->
      <div class="feedback-container">
        <h2 class="section-title">Ratings & Reviews</h2>
        <div class="feedback-list">
          <div
            v-for="(feedback, index) in feedbackList"
            :key="index"
            class="feedback-item"
            >
            <!--user rating -->
            <div class="user-info">
              <div class="stars user-stars">
                <span
                  v-for="star in 5"
                  :key="star"
                  :class="{ filled: star <= feedback.rating }"
                  >★
                </span>
              </div>
            </div>
            <!-- User Feedback -->
            <strong class="username">Anonymous User</strong>
            <p class="comment">{{ feedback.comment }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getToken, removeToken } from "@/utils/auth";

export default {
  name: "adminDashboard",
  data() {
    return {
      urlappphp: process.env.VUE_APP_URLAPPPHP,
      feedbackList: [], // feedback items
      feedbackRatings: [], // ratings feedbackList
      averageRating: 0,
      roundedRating: 0, 
      localUserData: {},
    };
  },

  methods: {
    async fetchFeedbacks() {
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
          body: JSON.stringify({ action: "getFeedbacks" }),
        });

        const result = await response.json();
        console.log("Feedback response:", result);

        if (result.success) {
          this.feedbackList = result.feedbacks;
          this.populateFeedbackRatings();
          this.calculateAverageRating();
        }else {
          console.error("Failed to fetch feedbacks:", result.error);
        }
      }catch (error) {
        console.error("Error fetching feedbacks:", error);
      }
    },

    populateFeedbackRatings() {
      this.feedbackRatings = this.feedbackList.map(
        (feedback) => feedback.rating
      );
    },

    calculateAverageRating() {
      const totalRatings = this.feedbackRatings.length;
      if (totalRatings === 0) {
        this.averageRating = 0;
        this.roundedRating = 0;
        return;
      }
      const sum = this.feedbackRatings.reduce((acc, rating) => acc + rating, 0);
      this.averageRating = (sum / totalRatings).toFixed(1);
      this.roundedRating = Math.floor(this.averageRating);
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
    this.fetchFeedbacks();
    this.priceInterval = setInterval(() => {
      this.fetchFeedbacks();
    }, 5000);
    document.addEventListener("click", this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
    clearInterval(this.priceInterval);
  },
};
</script>

<style scoped>
.ratings-reviews,
.overall-rating,
.feedback-container,
.feedback-item,
.feedback-container h2,
.overall-rating h3,
.overall-rating p {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

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
  background-color: #4a5568;  /* pressed  */
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

/* feeback */
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

.overall-rating:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4); 
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

.stars {
  font-size: 3rem; 
  text-align: center;
  transition: transform 0.3s ease;
}

.stars span {
  cursor: pointer;
  color: #666;
  transition: color 0.3s ease, transform 0.3s ease;
}

.stars span.filled {
  color: #ffd700;
  filter: drop-shadow(0 0 15px rgba(255, 223, 0, 0.8)); /* glow effect */
}

.stars span:hover {
  color: #ffcc00;
  transform: scale(1.2); /* zoom effect */
}

/* small stars) */
.user-stars {
  font-size: 1.5rem; 
  margin-bottom: 5px;
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

  .stars {
    font-size: 2rem
  }

  .user-stars {
    font-size: 1.2rem;
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

  .stars {
    font-size: 1.8rem;
  }

  .user-stars {
    font-size: 1rem;
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
