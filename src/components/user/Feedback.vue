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
      <li><a href="#" @click.prevent="$router.push('/')">Home</a></li>
      <li><a href="#" @click.prevent="$router.push('/price')">Price</a></li>
      <li><a href="#" @click.prevent="$router.push('/compare')">Compare</a></li>
      <li>
        <a href="#" @click.prevent="$router.push('/feedback')">Feedback</a>
      </li>
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
        <a href="#" @click.prevent="$router.push('/feedback')">Feedback</a>
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

  <div class="main-content">
    <div class="feedback-content">
      <!-- Feedback Form -->
      <div v-if="!feedbackSubmitted" class="feedback-form">
        <!--<img src="@/assets/cinemania_logo.png" alt="Cinemania Logo" class="logo" />-->
        <h2>HOW WAS YOUR EXPERIENCE</h2>
        <p>We value your feedback.</p>
        <p>Please rate your overall experience</p>

        <!-- Star Rating -->
        <div class="stars">
          <span
            v-for="star in 5"
            :key="star"
            :class="{ filled: star <= rating }"
            @click="setRating(star)"
            >★</span
          >
        </div>

        <label for="feedback" class="feedback-label"
          >Tell us how we can improve</label
        >
        <textarea
          id="feedback"
          v-model="feedback"
          class="feedback-input"
          required
        ></textarea>

        <button type="submit" class="btn" @click.prevent="submitFeedback">
          Submit
        </button>
      </div>

      <!-- Appreciation Message -->
      <div v-else class="appreciation-message">
        <!--<img src="@/assets/cinemania_logo.png" alt="Cinemania Logo" class="logo" />-->
        <div class="stars fixed">
          <span
            v-for="star in 5"
            :key="star"
            :class="{ filled: star <= rating }"
            >★</span
          >
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
    // Added sidebar methods
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
        this.rating = star; // Update the rating only if feedback is not submitted
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
/* Added navbar styles */
* {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

body {
  margin: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav {
  background-color: white;
  box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
  margin: 0;
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
}

nav li {
  height: 50px;
  margin: 0;
  padding: 0;
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
}

.sidebar li {
  width: 100%;
}

.sidebar a {
  width: 100%;
  font-size: 16px;
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

.main-content {
  padding-top: 0;
  margin-top: -100px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
}

.feedback-content {
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

.appreciation-message {
  margin-top: 20px;
}

.thank-you {
  margin-top: 20px;
  font-size: 1.5rem;
  color: #ffd700;
  font-weight: bold;
}
</style>
