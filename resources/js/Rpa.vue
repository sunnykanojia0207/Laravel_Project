<!-- <template>
    <div>
        <button @click="runRPA">Run RPA</button>
        <div v-if="loading" class="robot-animation">Running...</div>
        <div v-if="status === 'success'" class="robot-animation success">Success!</div>
        <div v-if="status === 'error'" class="robot-animation error">Error!</div>
        <p>{{ message }}</p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            loading: false,
            status: null,
            message: '',
        };
    },
    methods: {
        runRPA() {
    this.loading = true;
    this.status = null;
    this.message = '';

    axios.post('/api/rpa', {}, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        this.loading = false;
        this.status = response.data.status;
        this.message = response.data.message;
    })
    .catch(error => {
        this.loading = false;
        this.status = 'error';
        this.message = error.response.data.message || 'An error occurred';
    });
}

    }
}
</script>

<style>
.robot-animation {
    /* Basic animation styles */
    animation: bounce 1s infinite;
    font-size: 2rem;
    text-align: center;
    margin-top: 20px;
}

.robot-animation.success {
    color: green;
}

.robot-animation.error {
    color: red;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-30px);
    }
    60% {
        transform: translateY(-15px);
    }
}
</style> -->

<template>
    <div>
      <h2>RPA Bot Logs</h2>
      <button @click="startRpaProcess">Start RPA Process</button>
      <div v-if="logs.length">
        <pre v-for="(log, index) in logs" :key="index">{{ log }}</pre>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    
    data() {
      return {
        logs: []  // Array to store the real-time logs
      };
    },
    mounted() {
      // Listen for real-time logs from Laravel Echo WebSocket
      window.Echo.channel('rpa-logs')
        .listen('.log.message', (event) => {
          this.logs.push(event.log);  // Add new log to logs array
        });
    },
    methods: {
      startRpaProcess() {
        axios.post('/api/run-rpa')
          .then(response => {
            console.log("RPA Process started");
          })
          .catch(error => {
            console.error(error);
          });
      }
    }
  };
  </script>
  