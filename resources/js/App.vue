<template>
  <ul>
  <li><router-link to="/">Home</router-link></li>
  <li><router-link to="/login">Login</router-link></li>
  <li><router-link to="/register">Register</router-link></li>
  <li><router-link to="/profile">Profile</router-link></li>
  <li><router-link to="/userlist">Userlist</router-link></li>
  <li><router-link to="/rpa">RPA</router-link></li>
  </ul>
  
  <main>
  <router-view></router-view>
  </main>
</template>
<script>
export default{
  name : 'App'
}
</script>
<style scoped>
main {
  flex: 1;
  background-image: url(C:\xampp\htdocs\laravel10-vue3-main\laravel10-vue3-main\public\images\pexels-jplenio-1103970.jpg);
  background-repeat: no-repeat;
  background-size: cover;
  background-attachment: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 93vh; /* Ensure main covers the entire viewport */
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
  
  
}

li {
  float: left;
  border-right:1px solid #bbb;
}

li:last-child {
  border-right: none;
}

li a {
  font-family: 'Times New Roman', Times, serif;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #36b89a;
}

</style>