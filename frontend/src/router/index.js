import { createRouter, createWebHashHistory} from 'vue-router'
import Home from '../components/Home.vue'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import Profile from '../components/user/Profile.vue'
import PaymentSuccess from '../components/payments/PaymentSuccess.vue'
import { useAuthStore } from '../stores/useAuthStore'


function checkIfLoggedIn() {
    const store = useAuthStore()
    if(!store.access_token) return '/login'
}

function checkIfNotLoggedIn() {
    const store = useAuthStore()
    if(store.access_token) return '/'
}


const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            beforeEnter: [checkIfLoggedIn]
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            beforeEnter: [checkIfNotLoggedIn]
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            beforeEnter: [checkIfNotLoggedIn]
        }, 
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
            beforeEnter: [checkIfLoggedIn]
        }, 
        {
            path: '/payment/success/:hash',
            name: 'success',
            component: PaymentSuccess,
            beforeEnter: [checkIfLoggedIn]
        }
    ]
})

export default router