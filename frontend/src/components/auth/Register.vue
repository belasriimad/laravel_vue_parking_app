<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-6 mx-auto">
                <ValidationErrors :errors="data.errors" />
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Register
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="registerUser">
                            <div class="mb-3">
                                <input type="text" 
                                    v-model="data.user.name"
                                    placeholder="Name*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <input type="email" 
                                    v-model="data.user.email"
                                    placeholder="Email*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <input type="password" 
                                    v-model="data.user.password"
                                    placeholder="Password*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <Spinner v-if="data.loading" />
                                <button
                                    v-else
                                    type="submit"
                                    class="btn btn-sm btn-dark rounded-0">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive } from "vue"
    import axios from 'axios'
    import { BASE_URL } from '../../helpers/baseUrl'
    import ValidationErrors from '../layouts/ValidationErrors.vue'
    import Spinner from '../layouts/Spinner.vue'
    import { useAuthStore } from '../../stores/useAuthStore'
    import router from '../../router'

    const store = useAuthStore()

    const data = reactive({
        user: {
            name: '',
            email: '',
            pasword: ''
        },
        errors: null,
        loading: false
    })

    const registerUser = async () => {
        data.loading = true
        try {
            const response = await axios.post(`${BASE_URL}/user/register`, data.user)
            store.setToken(response.data.access_token)
            store.setUser(response.data.user)
            data.loading = false
            router.push('/')
        } catch (error) {
            if(error?.response?.status === 422) {
                data.errors = error.response.data.errors
            }
            data.loading = false
            console.log(error);
        }
    }

    
</script>

<style>

</style>