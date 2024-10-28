import { createWebHistory, createRouter } from 'vue-router'
import Home from './Home.vue';
import Profile from './Profile.vue';
import Login from './Login.vue';
import Register from './Register.vue';
import UserList from './UersList.vue';
import Edit from './Edit.vue';
import ViewProfile from './ViewProfile.vue';
import Chart from './Charts.vue';
import Rpa from './Rpa.vue';

const routes = [
  { 
    name:'Home',
    path: '/',
    component: Home 
  },
  { 
    name:'Login',
    path: '/login',
    component: Login 
  },
  { 
    name:'Profile',
    path: '/profile',
    component: Profile
  },
  { 
    name:'Register',
    path: '/register',
    component: Register
  },
  
  { 
    name:'UserList',
    path: '/userlist',
    component: UserList
  },
  { 
    name:'Edit',
    path: '/edit/:id',
    component: Edit
  },
  { 
    name:'ViewProfile',
    path: '/viewprofile/:id',
    component: ViewProfile
  },
  { 
    name:'Chart',
    path: '/chart',
    component: Chart
  },
  { 
    name:'Rpa',
    path: '/rpa',
    component: Rpa
  }

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router;