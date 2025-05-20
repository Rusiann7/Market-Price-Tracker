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

  <div v-if="isLoading" class="loading-screen">
    <div class="loading-spinner"></div>
    <p>Loading...</p>
  </div>

  <div v-show="!isLoading"> 

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="main-content">
    <div class="main-container">
      <h2>News and Updates</h2>
      <div class="news-list">
        <div v-for="(newsItem, index) in newsList" :key="index" class="news-item">
          <!-- news items -->
          <strong class="title">{{ newsItem.title }}</strong>
          <br>
          <br>
          <p class="comment">{{ newsItem.description }}</p>
          <br>
          <p class="date">Date: {{ newsItem.published_at }}</p>
          <br>
          <button type="submit" class="btn" @click.prevent="openLink(newsItem.url)">
            Read More
          </button>
        </div>
      </div>
    </div>
  </div>
  </div>
    
</template>

<script>
export default {
  name: "NewsUpdates",
  data() {
    return {
      urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
      newsList: [],
      isLoading: true,
    }
  },

  methods: {
    async getNews() {
      try {
        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ action: "newsandUpdates" }),
        });

        const result = await response.json();
        console.log("News response:", result);

        if (result.success) {
          this.newsList = result.data.data.filter(item => 
          /economy|price|inflation|market|goods|supply/i.test(item.title + item.description) || true); // if nothing matches, just return everything
        }else {
          console.error("Failed to fetch news:", result.error);
        }
      }catch (error) {
        console.error("Error fetching news:", error);
      } finally {
      this.isLoading = false;
      }
    },

    showSidebar() {
      this.$refs.sidebar.style.display = "flex";
    },

    hideSidebar() {
      this.$refs.sidebar.style.display = "none";
    },

    openLink(url) {
      window.open(url, "_blank");
    }
  },

  mounted(){
    this.getNews();
  }
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

.main-content {
  position: relative; /* above overlay */
  z-index: 2; /* content is above the image */
  padding: 20px;
  color: white;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  margin-top: 50px;
}

.main-container {
  width: 100%; 
  background-color: #232831;
  color: #ffffff;
  max-height: 70vh; 
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px; 
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  margin: 25px ;
  scrollbar-width: thin;
  scrollbar-color: #4a5568 #2d3748;
}

.news-item {
  border-top: 2px solid #ddd;
  padding: 15px;
  transition: background-color 0.3s ease;
  width: 100%;
  text-align: left;
}

.news-item:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  background-color: #4a5568;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
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