<template>
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
        </a>
      </li>
    </ul>
  </nav>

 <div v-if="captcha" key="captcha" class="loading-screen">
    <h1>Verify You're Human</h1>
    <p>Complete the CAPTCHA to continue.</p>
    <div class="cf-turnstile" data-sitekey="0x4AAAAAABeBd5qJe7k3qqQy" data-callback="onSuccess" data-theme="dark"></div>
  </div>

  <div v-if="isLoading && !captcha" class="loading-screen">
    <div class="loading-spinner"></div>
    <p>Loading...</p>
  </div>

  <div v-show="!isLoading && !captcha"> 

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="top-text">
    <h1>Compare Prices</h1>
  </div>

  <div class="main-content"> 
    <div class="prices-container">
      <div class="compare-content"><!--Latest Content-->

        <h2>Latest Prices</h2>
        
        <div class="price-list">
          <table class="table-content">
            <thead>
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Source</th>
              </tr>
            </thead>

            <tbody>
              <tr 
                v-for="price in prices" 
                :key="price.RiceType + price.Source" 
                
              >
                <td>{{ price.RiceType }}</td>
                <td>₱{{ price.Price }}</td>
                <td><a :href="price.Source" target="_blank" rel="noopener noreferrer" @click.stop style="color: black;">Source</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>  

        <div class="compare-content"> 

          <h2>Older Prices</h2>
          <div class="button-row">

            <button @click="increaseCounter" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" 
              height="24px" viewBox="0 -960 960 960" 
              width="24px" fill="#000000">
              <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
            </svg>
            </button>

            <div class="counter">
              {{counter}}
            </div>

            <button @click="decreaseCounter" class="btn">
              <svg xmlns="http://www.w3.org/2000/svg" 
                height="24px" viewBox="0 -960 960 960" 
                width="24px" fill="#000000">
                <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/>
              </svg>
            </button>

          </div>

          <div class="price-list">
            <table class="table-content">
              <thead>
                <tr>
                  <th>Products</th>
                  <th>Price</th>
                  <th>Source</th>
                </tr>
              </thead>

              <tbody>
                <tr 
                  v-for="price in comparePrices" :key="'compare-'+price.RiceType">
                  <td>{{ price.RiceType }}</td>
                  <td>₱{{ price.Price }}</td>
                  <td><a :href="price.Source" target="_blank" rel="noopener noreferrer" @click.stop style="color: black;">Source</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      
    </div>
  </div>
  </div>
</template>

<script>
  export default{

    name: 'comPare',
    data(){
      return {
      urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
      isLoading: true,
      captcha: true,
      prices: [],
      comparePrices: [],
        counter: 1,
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

      async getPrices(){
      try {

        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ action: "getPrices" }),
        });

        const result = await response.json();

        if (result.success) {
          this.prices = result.data.map(item => ({
          ...item,
          Price: typeof item.Price === 'number' ? item.Price.toFixed(2) : item.Price
          }));

        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
      } finally {
      this.isLoading = false;
      }
    },
    
    async getCompare(){
      this.isLoading = true;
      try {

        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ action: "getCompare", counter: this.counter }),
        });

        const result = await response.json();

        if (result.success) {
          this.comparePrices = result.data.map(item => ({
          ...item,
          Price: typeof item.Price === 'number' ? item.Price.toFixed(2) : item.Price
          }));

        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
      } finally {
      this.isLoading = false;
      }
    },

    async captchaVerify() {
      try {
        if (document.cookie.includes("cf_verified=1")) {
          this.captcha = false;
          await this.getPrices(); // Get prices after verification
        }
      } catch (error) {
        console.error('Error verifying captcha:', error);
      }
    },

    increaseCounter(){
      this.counter++;
      this.getCompare();
    },

    decreaseCounter(){
      if (this.counter > 1) {
        this.counter--;
        this.getCompare();
      }
    },
  },

    mounted(){
      window.onSuccess = async () => {
      document.cookie = "cf_verified=1; path=/; max-age=10800";
      this.captcha = false;
      await this.getPrices(); // Get prices after successful verification
    };

    this.captchaVerify();
  
    // Only set interval if not showing captcha
    if (!this.captcha) {
      this.getCompare();
      this.priceInterval = setInterval(() => {
        this.getPrices();
      }, 5000);
    }
      document.addEventListener("click", this.handleClickOutside);
    },

    beforeUnmount() {
      document.removeEventListener("click", this.handleClickOutside);
      clearInterval(this.priceInterval);
    },
  }
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

.latest-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 90%;
  max-width: 1200px;
  margin: 20px auto;
  gap: 40px;
  position: relative; /* above overlay */
  z-index: 2;
  background-color: #232831;
  color: white;
  max-height: 70vh;
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.latest-container h2 {
  margin-bottom: 20px;
  font-size: 1.4rem;
  font-weight: bold;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.prices-container {
  position: relative; 
  z-index: 2;
  display: flex;
  flex-direction: row; 
  justify-content: space-between;
  align-items: flex-start;
  width: 90%;
  max-width: 1200px;
  margin: 20px auto;
  gap: 30px;
}

.table-content {
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 0.9rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-width: 300px;
  width: 100%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  overflow: hidden;
  border-radius: 15px;
}

.table-content thead tr {
  background-color: #1e1e1e;
  color: #ffffff;
  text-align: left;
  font-weight: bold;
}

.table-content tbody tr {
  background-color: #f9f9f9;
  color: #333;
  border-bottom: 1px solid #dddddd;
}

.table-content tbody tr:nth-of-type(even) {
  background-color: #f3f3f3; /* Changed to be different from odd rows */
}

.table-content tbody tr:hover {
  background-color: #e9e9e9; /* hover effect */
  cursor: pointer; /* show if rows are clickable */
}

.table-content th,
.table-content td {
  padding: 12px 15px;
}

.table-content tbody tr:last-of-type {
  border-bottom: #1e1e1e;
}

.btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  padding: 0;
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

.btn svg {
  fill: #000000;
}

.counter {
  min-width: 30px;
  text-align: center;
  font-size: 1.1rem;
  font-weight: bold;
  color: white;
}

.compare-content {
  width: 80%; 
  max-width: none; 
  background-color: #232831;
  color: white;
  max-height: 70vh;
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  margin: 0;
}


.compare-content::-webkit-scrollbar {
  width: 8px;
}

.compare-content::-webkit-scrollbar-track {
  background: #232831;
}

.compare-content::-webkit-scrollbar-thumb {
  background-color: #3a4252;
  border-radius: 4px;
}

.compare-content h2 {
  margin-bottom: 20px;
  font-size: 1.4rem;
  font-weight: bold;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.main-content {
  max-width: 1400px;
  margin: 40px auto;
  padding: 0 20px;
}

.button-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 20px;
  background-color: rgba(255, 255, 255, 0.05);
  padding: 12px;
  border-radius: 8px;
  margin: 10px auto 20px;
  width: fit-content;
}

.top-text{
  position: relative; /* above overlay */
  z-index: 2;
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 90px;
  color: #ffffff;
}

@media (max-width: 768px) {
  .prices-container {
    flex-direction: column;
    align-items: center;
  }

  .compare-content {
    width: 95%;
    margin: 0 auto;
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

@media (max-width: 768px) {
  .table-content {
    min-width: 100%;
    font-size: 0.85rem;
  }

  .table-content th,
  .table-content td {
    padding: 10px 12px;
  }

  .main-content {
    padding: 0 10px;
  }
}

@media (max-width: 480px) {
  .table-content {
    font-size: 0.8rem;
  }

  .table-content th,
  .table-content td {
    padding: 8px 10px;
  }

  .compare-content {
    padding: 15px;
  }
}
</style>
