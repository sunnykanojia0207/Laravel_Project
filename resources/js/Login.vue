<template>
  <div class="login-form">
    <h2>Login</h2>

    <div class="container">
      <div class="mb-3">
        <label for="email"><b>E-mail</b></label>
        <input type="text" placeholder="Enter Email" v-model="email">
        <ul class="error-list" v-if="errorList.email">
          <li class="error-item" v-for="(error, index) in errorList.email" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="password"><b>Password</b></label>
        <div class="password-input">
          <input :type="showPassword ? 'text' : 'password'" placeholder="Enter Password" v-model="password">
          <span class="toggle-password" @click="togglePasswordVisibility">
            <i :class="['bi', showPassword ? 'bi-eye' : 'bi-eye-slash']"></i>
          </span>
        </div>
        <ul class="error-list" v-if="errorList.password">
          <li class="error-item" v-for="(error, index) in errorList.password" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <button @click="loginUser">Login</button> <br>
      <router-link to="/register" class="dont">Don't have an account? Register.</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      email: '',
      password: '',
      errorList: {} ,// Added errorList data property
      isLoggedIn: localStorage.getItem('isLoggedIn') === 'false',
      showPassword: false
    };
  },
  methods: {
    loginUser() {
      axios.post('/api/login', {
        email: this.email,
        password: this.password
      })
        .then(response => {
          console.log(response.data);
          alert("Logged in Successfully");
          // this.isLoggedIn = true; // Set isLoggedIn to true
          // localStorage.setItem('isLoggedIn', 'true'); 
          // localStorage.setItem('userData', JSON.stringify(response.data.user)); 
          // Redirect to the dashboard or home page
          // You can use Vue Router for redirection
          this.$router.push('/profile');
        })
        .catch(error => {
          if (error.response) {
            if (error.response.status === 422) {
              this.errorList = error.response.data.errors;
            }
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
             console.error('Login failed:', error.response.data);
             alert("Login failed. Please check your credentials.")
        });
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    }
  }
}
</script>

<style scoped>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.login-form {
  width: 50%;
  border: 3px solid #f1f1f1;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Add drop shadow */
  text-align: center;
}

.container {
  padding: 16px;
}

input[type=text],
input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); 
}

.password-input {
  position: relative;
}

.toggle-password {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.9;
}

.error-list {
  list-style-type: none; /* Remove bullet points */
  padding: 0; /* Remove default padding */
}

.error-item {
  margin-bottom: 5px; /* Add margin between error messages */
  color: #721c24; /* Custom color for error messages */
  background-color: #f8d7da; /* Custom background color for error messages */
  border: 1px solid #f5c6cb; /* Custom border color for error messages */
  padding: 10px; /* Add padding to the error messages */
  border-radius: 5px; /* Add border radius to the error messages */
}

ul {
  margin-top: 5px;
}
.bi{
  font-size: 1.2rem;
}
.dont{
  color: black;
  font-weight: bold; 
  font-family: 'Times New Roman', Times, serif;/* Change text color to white */
}
</style>
