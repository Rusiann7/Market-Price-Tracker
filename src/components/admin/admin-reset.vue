<template>

    <div class="image-container">
        <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
        <div class="img-overlay"></div>
    </div>

    <div class="container">
        
        <div class="top-text" v-if="$route.path === '/adminReset'">
            <h1>Reset Password</h1>
        </div>

        <div class="top-text" v-if="$route.path === '/code'">
            <h1>Reset Password</h1>
        </div>

        <div class="top-text" v-if="$route.path === '/password'">
            <h1>Reset Password</h1>
        </div>

        <div class="main-content">
            <div v-if="$route.path === '/adminReset'">
                <center>
                
                <p>Email: </p>
                <form
                id="email"
                method="get"
                @submit.prevent="email"
                >
    
                <input
                type="email"
                class="input-field"
                placeholder="Email"
                v-model="resetData.email"
                required
                >
    
                <button type="submit" class="btn" value="POST">Continue</button>
    
                </form>
                </center>
            </div>
    
            <div v-if="$route.path === '/code'">
                <center>
    
                    <p>Enter code from Email: </p>
                    <form
                    id="code"
                    method="get"
                    @submit.prevent="code"
                    >
            
                        <input
                        type="text"
                        class="input-field"
                        placeholder="Code"
                        v-model="resetData.code"
                        required
                        >
    
                        <button type="submit" class="btn" value="POST">Enter</button>

                    </form>
                </center>
            </div>
    
            <div v-if="$route.path === '/password'">
                <center>
                    <form
                    id="password"
                    method="get"
                    @submit.prevent="password"
                    >

                        <p>New Password: </p>
                        <input
                        type="password"
                        class="input-field"
                        placeholder="New Password"
                        v-model="resetData.initPassword"
                        >
    
                        <p>Confirm Password: </p>
                        <input
                        type="password"
                        class="input-field"
                        placeholder="Confirm Password"
                        v-model="resetData.conPassword"
                        >
    
                        <button type="submit" class="btn" value="POST">Confirm</button>
                    
                    </form>
                </center>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AdminReset',
        data() {
            return {
                resetData: {
                    email: "",
                    code: "",
                    initPassword: "",
                    conPassword: ""
                },
                urlappphp: process.env.VUE_APP_URLAPPPHP,
            }
        },
    
        methods: {
            async email() {
                try {                        
                    const response = await fetch(this.urlappphp, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            },
                        body: JSON.stringify({
                            resetData: {
                                email: this.resetData.email,
                                action: "reset"
                            }}),
                    });
                    
                    const result = await response.json();
                    if (result.success) {// ito yung routes
                        localStorage.setItem('resetEmail', JSON.stringify(this.resetData.email))
                        this.$router.push('/code');
                    } else {
                        this.responseMessage = result.error || "Login failed";
                        }
                } catch (error) {
                    console.error("Error:", error);
                    this.responseMessage = "Failed to communicate with the server.";
                }
            },
    
            async code(){
                try {
                    const storedEmail = localStorage.getItem('resetEmail');
                    const response = await fetch(this.urlappphp, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            },
                        body: JSON.stringify({
                            resetData: {
                                email: JSON.parse(storedEmail),
                                code: this.resetData.code,
                                action: "code"
                            }}),
                    });
                    const result = await response.json();
                    if (result.success) {// ito yung routes
                        this.$router.push('/password');
                    } else {
                        this.responseMessage = result.error || "Login failed";
                    }
                } catch(error) {
                    alert("Error")
                }
            },
    
            async password(){
                try{
                    const storedEmail = localStorage.getItem('resetEmail');
                    const response = await fetch(this.urlappphp, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            },
                        body: JSON.stringify({
                            resetData: {
                                email: JSON.parse(storedEmail),
                                initPassword: this.resetData.initPassword,
                                conPassword: this.resetData.conPassword,
                                action: "password"
                            }}),
                    });
                    const result = await response.json();
                    if (result.success) {// ito yung routes
                        localStorage.removeItem('resetEmail');
                        alert("Password updated successfully!");
                        this.$router.push('/login');
                    } else {
                        this.responseMessage = result.error || "Login failed";
                    }
                }catch(error){
                    alert("Error")
                }
            }
        }
    }
</script>
    
<style scoped>
    
.main-content{
    position: relative;
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