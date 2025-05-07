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

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="main-content">
    <div class="feedback-content">
      <!-- feedback Form -->
      <div v-if="!feedbackSubmitted" class="feedback-form">
        <h2>HOW WAS YOUR EXPERIENCE</h2>
        <p>We value your feedback.</p>
        <p>Please rate your overall experience</p>

        <!-- star -->
        <div class="stars">
          <span
            v-for="star in 5"
            :key="star"
            :class="{ filled: star <= rating }"
            @click="setRating(star)"
            >★</span>
        </div>

        <label for="feedback" class="feedback-label"
          >Tell us how we can improve</label>

        <textarea
          id="feedback"
          v-model="feedback"
          class="feedback-input"
          required
          >
        </textarea>

        <button type="submit" class="btn" @click.prevent="submitFeedback">
          Submit
        </button>
      </div>

      <!-- thank you msg -->
      <div v-else class="appreciation-message">
        <div class="stars fixed">
          <span
            v-for="star in 5"
            :key="star"
            :class="{ filled: star <= rating }"
            >★
          </span>
        </div>
        
        <p class="thank-you">Thank you for your feedback!</p>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FeedBack",
  data() {
    return {
      FeedbackData: {
        feedback: "",
        rating: 0,
      },
      feedbackSubmitted: false,
      responseMessage: null,
      feedback: "",
      rating: 0,
      urlappphp: process.env.VUE_APP_URLAPPPHP,
    };
  },

  methods: {
    showSidebar() {
      this.$refs.sidebar.style.display = "flex";
    },

    hideSidebar() {
      this.$refs.sidebar.style.display = "none";
    },

    async submitFeedback() {
      try {

        if (this.rating < 1) {
          alert("Please select at least 1 star");
          return;
        }

        const feedbackData = {
          action: "feedback",
          feedback: this.feedback,
          rating: this.rating,
        };

        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(feedbackData),
        });

        const result = await response.json();

        if (result.success) {
          this.feedbackSubmitted = true;
          this.responseMessage = "Success";
        } else {
          alert(result.error || "Plese select atleast 1 star");
        }
      } catch (error) {
        this.resposeMessage = "Failed to communicate with the server";
      }
    },

    setRating(star) {
      if (!this.feedbackSubmitted) {
        this.rating = star; // resets after submission
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
  },

  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
};
</script>

<style scoped>
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

.main-content {
  padding-top: 0;
  margin-top: -100px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
}

.feedback-content {
  position: relative;
  z-index: 2;
  max-width: 500px;
  margin-top: 250px;
  color: #ffffff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding: 30px;
  text-align: center;
  background-color: #232831;
  border-radius: 15px;  /* rounded corners */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.logo {
  display: block;
  margin: 0 auto 5px;
  margin-top: 50px;
  width: 150px;
}

.feedback-content h1 {
  text-align: center;
  margin-bottom: 10px;
  font-size: 1.5rem;
}

.feedback-content p {
  text-align: center;
  font-size: 1rem;
}

.stars {
  text-align: center;
  font-size: 4rem;
  margin: 20px 0;
}

.stars span {
  cursor: pointer;
  color: #666;
  transition: color 0.3s ease;
  margin: 0 5px;
}

.stars span.filled {
  color: #ffd700;
  filter: drop-shadow(0 0 15px rgba(255, 223, 0, 0.8));
}

.feedback-label {
  display: block;
  margin-top: 20px;
  font-size: 1rem;
  text-align: left;
}

.feedback-input {
  width: 100%;
  height: 100px;
  margin-top: 10px;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  background: #fff;
  color: #000;
  font-family: inherit;
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

.appreciation-message {
  margin-top: 20px;
}

.thank-you {
  margin-top: 20px;
  font-size: 1.5rem;
  color: #ffd700;
  font-weight: bold;
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
