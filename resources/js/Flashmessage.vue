<template>
    <div class="flash-message">
      <p>
        <span>{{ message }}</span>
        <button @click="close">&#10005;</button>
      </p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      message: {
        type: String,
        required: true
      }
    },
    methods: {
      close() {
        this.$emit('close');
        this.$router.push('/login');
      }
    }
  }
  </script>
  
  <style scoped>
  .flash-message {
    background-color: #ee730f; /* Yellow background color */
    color: #ffffff; /* Black text color */
    padding: 10px; /* Padding around the message */
    border: 1px solid #ccc; /* Border around the message */
    border-radius: 5px; /* Rounded corners */
    margin-top: 10px; /* Margin to separate from other content */
    display: flex; /* Use flexbox */
    justify-content: space-between; /* Align items with space between them */
    align-items: center; /* Align items vertically */
  }
  
  .flash-message p {
    margin: 0; /* Remove default margin */
    text-align: left; /* Align text to the left */
    flex: 1; /* Take up remaining space */
  }
  
  .flash-message button {
    background-color: transparent; /* Transparent background color for the button */
    border: none; /* No border for the button */
    cursor: pointer; /* Cursor pointer on hover */
    color: #ffffff; /* Black text color */
    font-size: 1.2rem; 
    margin-left: 122px;/* Adjust the font size */
  }
  
  .flash-message button:hover {
    text-decoration: underline; /* Underline text on hover */
  }
  </style>
  