<template>
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

  <div class="price-container">
    <h1 style="color: black">Prices & Analytics</h1>

    <div class="prices-container">
      <!-- Chart Container (Left Side) -->
      <div class="chart-section">
        <h3>Price Trends</h3>
        <div class="chart-wrapper">
          <Bar :data="chartData" :options="chartOptions" />
        </div>
      </div>

      <!-- Price List Container (Right Side) -->
      <div class="list-price-container">
        <h2 class="section-title">Market Prices</h2>
        <div class="price-list">
          <table>
            <tr>
              <th>Rice Type</th>
              <th>Price</th>
              <th>Source</th>
            </tr>
  
            <tr v-for="price in prices" :key="price.RiceType + price.Source">
              <td>{{ price.RiceType }}</td>
              <td>{{ price.Price }}</td>
              <td>{{ price.Source }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

export default {
  name: "PricePage",
  components: {
    Bar,
  },
  data() {
    return {
      urlappphp: process.env.VUE_APP_URLAPPPHP,
      prices: [],
      chartData: {
        labels: ["1", "2", "3", "4", "5"],
        datasets: [
          {
            label: "Price Data",
            data: [10, 20, 15, 25, 30],
            backgroundColor: "blue",
          },
        ],
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        scales: {
          x: { display: false },
          y: { beginAtZero: true },
        },
      },
    };
  },
  methods: {
    showSidebar() {
      this.$refs.sidebar.style.display = "flex";
    },
    hideSidebar() {
      this.$refs.sidebar.style.display = "none";
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
        console.log("Price response:", result);

        if (result.success) {
          this.prices = result.prices;
        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
      }
    },
  },
  mounted(){
    this.getPrices();
  }
};
</script>

<style scoped>
.price-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  padding: 40px;
  border-radius: 12px;
  max-width: 100%;
  min-height: 45vh;
  margin: 0 auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
}

/* Main container for the two columns */
.prices-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 90%;
  max-width: 1200px;
  margin: 20px auto;
  gap: 40px;
}

/* Chart Section (Left Side) */
.chart-section {
  width: 30%;
  background-color: #1e1e1e;
  color: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: sticky;
  top: 20px;
}

.chart-section h3 {
  text-align: center;
  margin-bottom: 20px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.chart-wrapper {
  width: 100%;
  margin: 0 auto;
}

/* Price List Container (Right Side) */
.list-price-container {
  width: 65%;
  background-color: #fff;
  color: #333;
  max-height: 70vh;
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.list-price-container h2 {
  margin-bottom: 20px;
  font-size: 1.4rem;
  font-weight: bold;
  color: #333;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Hover effect for both containers */
.chart-section:hover,
.list-price-container:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

.price-list {
  padding: 10px;
}

.chart-container {
  width: 300px;
  height: 200px;
  margin: 20px auto;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1200px;
  gap: 40px;
  display: flex;
}

@media (max-width: 1024px) {
  .prices-container {
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  .chart-section,
  .list-price-container {
    width: 90%;
    max-width: 600px;
  }

  .chart-section {
    position: relative;
    top: 0;
  }
}

@media (max-width: 768px) {
  .prices-container {
    gap: 15px;
  }

  .chart-section,
  .list-price-container {
    width: 100%;
  }

  .chart-section h3 {
    font-size: 18px;
  }

  .list-price-container {
    max-height: 350px;
  }

  .chart-container {
    width: 250px;
    height: 150px;
  }
}

@media (max-width: 480px) {
  .chart-section h3 {
    font-size: 16px;
  }

  .chart-container {
    width: 200px;
    height: 120px;
  }
}

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
</style>
