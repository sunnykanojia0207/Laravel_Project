<template>
  <div class="register-form">
    <h1> Register</h1>
    <div class="container">
       <!-- Flash message component -->
       <flash-message v-if="showFlashMessage" :message="flashMessage" @close="closeFlashMessage" />
      <div class="mb-3">
        <label for="fullname"><b>Full Name</b></label>
        <input type="text" placeholder="Enter Full Name" v-model="fullname">
        <ul class="error-list" v-if="errorList.fullname">
          <li class="error-item" v-for="(error, index) in errorList.fullname" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="lastname"><b>Last Name</b></label>
        <input type="text" placeholder="Enter Last Name" v-model="lastname">
        <ul class="error-list" v-if="errorList.lastname">
          <li class="error-item" v-for="(error, index) in errorList.lastname" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>


      <div class="mb-3">
        <label for="dob"><b>Date of Birth</b></label><br>
        <input type="date" v-model="dob">
      </div>
      
      <!-- Error list for date of birth -->
      <ul class="error-list" v-if="errorList.dob">
        <li class="error-item">{{ errorList.dob }}</li>
      </ul>
      

      <div class="mb-3">
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" v-model="email">
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

      <div class="mb-3">
        <button type="button" @click="registerUser">Register</button>
        <router-link to="/login" class="already">Already have an account? Login</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import FlashMessage from './FlashMessage.vue';

export default {
  components: {
    FlashMessage
  },
  data() {
    return {
      errorList: '',
      fullname: '',
      lastname: '',
      email: '',
      password: '',
      dob:'',
      showPassword: false,
      showFlashMessage: false,
      flashMessage: ''
    };
  },
  methods: {
    registerUser() {
     // Calculate age from date of birth
      const age = this.calculateAge(this.dob);
      // if (age < 18) {
      //   // Display flash message if age is less than 18
      //   this.flashMessage = 'You must be at least 18 years old to register.';
      //   this.showFlashMessage = true;
      //   return;
      // }
      var mythis = this;
      axios.post('/api/register', {
        fullname: this.fullname,
        lastname: this.lastname,
        email: this.email,
        password: this.password,
        dob: this.dob
      })
        .then(response => {
          console.log(response.data);
          alert("data update Successfully");
          if (age < 18) {
      // Display flash message if age is less than 18
      this.flashMessage = 'You have been registered successfully, but you have limited access to features.';
      this.showFlashMessage = true;
    }
    
          // Optionally, handle success response
        })
        .catch(function (error) {
          if (error.response) {

            if (error.response.status == 422) {
              mythis.errorList = error.response.data.errors;
            }

            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.error(error.response.data);
          // Optionally, handle error response
        });
    },
    calculateAge(dateString) {
      const today = new Date();
      const birthDate = new Date(dateString);
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    },
    closeFlashMessage() {
      this.showFlashMessage = false;
      this.flashMessage = '';
    },
    onFileChange(event) {
      this.image = event.target.files[0];
    },
    getFullname() {
      axios.get('/api/fullname') // Assuming '/api/fullname' is the endpoint to fetch the full name
        .then(response => {
          this.fullname = response.data.fullname;
        })
        .catch(error => {
          console.error('Error fetching full name:', error);
        });
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    mounted() {
      this.getFullname(); // Call getFullname method when the component is mounted
    }
  }
}
</script>
<!-- <style scoped>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.register-form {
  border: 3px solid #f1f1f1;
}

.container {
  padding: 16px;
}

input[type=text],
input[type=email],
input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  /* Adjust width to fit both buttons */
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
button:hover {
  opacity: 0.8;
}

.error-list {
  list-style-type: none;
  /* Remove bullet points */
  padding: 0;
  /* Remove default padding */
}

.error-item {
  margin-bottom: 1px;
  /* Add margin between error messages */
  color: #721c24;
  /* Custom color for error messages */
  background-color: #f8d7da;
  /* Custom background color for error messages */
  border: 1px solid #f5c6cb;
  /* Custom border color for error messages */
  padding: 10px;
  /* Add padding to the error messages */
  border-radius: 5px;
  /* Add border radius to the error messages */
}

ul {
  margin-top: 5px;
}
.bi{
  font-size: 1.2rem;
}
</style> -->
<style scoped>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.register-form {
  margin-top: 10px;
  margin-bottom:10px ;
  width: 50%;
  /* border: 3px solid #f1f1f1; */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Add drop shadow */
  text-align: center; /* Center align content */
}

.container {
  padding: 16px;

}

h1 {
  margin-top: 0; /* Remove default margin */
}

input[type=text],
input[type=email],
input[type=password],
input[type=date] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Add drop shadow to placeholders */
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  /* Adjust width to fit both buttons */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Add drop shadow to buttons */
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

button:hover {
  opacity: 0.8;
}

.error-list {
  list-style-type: none;
  /* Remove bullet points */
  padding: 0;
  /* Remove default padding */
}

.error-item {
  margin-bottom: 1px;
  /* Add margin between error messages */
  color: #721c24;
  /* Custom color for error messages */
  background-color: #f8d7da;
  /* Custom background color for error messages */
  border: 1px solid #f5c6cb;
  /* Custom border color for error messages */
  padding: 10px;
  /* Add padding to the error messages */
  border-radius: 5px;
  /* Add border radius to the error messages */
}

ul {
  margin-top: 5px;
}

.bi {
  font-size: 1.2rem;
}

.already {
  color:black;
  font-family: 'Times New Roman', Times, serif;
  font-weight: bold; /* Change text color to white */
}
</style>
