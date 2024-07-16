<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <router-link class="navbar-brand" to="/">
                <img src="https://cdn.pixabay.com/photo/2013/07/12/14/43/car-148642_1280.png"
                    height="60" width="60" alt="logo">
            </router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul v-if="!store.user" class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/register">
                            <i class="bi bi-person-add"></i> Register
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/login">
                            <i class="bi bi-box-arrow-right"></i> Login
                        </router-link>
                    </li>
                </ul>
                <ul v-else class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" aria-current="page" 
                            to="/">
                            <i class="bi bi-house-door-fill"></i> Home
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" aria-current="page" 
                            to="/profile">
                            <i class="bi bi-person-check-fill"></i> {{ store.user.name }}
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="#"
                            @click="userLogout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
    import { BASE_URL } from '../../helpers/baseUrl'
    import { useAuthStore } from '../../stores/useAuthStore'
    import { getHeaderConfig } from '../../helpers/headerConfig'
    import { onMounted } from 'vue'
    import axios from 'axios'
    import router from '../../router'
    import { useToast } from 'vue-toastification'

    const toast = useToast()

    const store = useAuthStore()

    const userLogout = async () => {
        try {
            const response = await axios.post(`${BASE_URL}/user/logout`, {}, 
                getHeaderConfig(store.access_token))
            store.clearStoredData()
            toast.success('Logout successfully', {
                timeout: 3000
            })
            router.push('/login')
        } catch (error) {
            console.log(error)
        }
    }


    const fetchCurrentUser = async () => {
        try {
            const response = await axios.get(`${BASE_URL}/user`, getHeaderConfig(store.access_token))
            store.setUser(response.data.user)
        } catch (error) {
            if(error?.response?.status === 401) {
                store.clearStoredData()
            }
            console.log(error)
        }
    }

    onMounted(() => fetchCurrentUser())

</script>

<style>

</style>