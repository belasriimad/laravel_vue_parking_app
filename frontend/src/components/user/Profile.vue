<template>
    <div class="container">
        <div class="row my-5">
            <div class="row my-2">
                <div class="col-md-6 mx-auto">
                    <ValidationErrors :errors="data.errors" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Update your username
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateProfile">
                            <div class="mb-3">
                                <input type="text" 
                                    v-model="data.name"
                                    placeholder="Name*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <Spinner v-if="data.loading.updatingName" />
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
             <div class="col-md-6">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="text-center mt-2">
                            Update your password
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updatePassword">
                            <div class="mb-3">
                                <input type="password" 
                                    v-model="data.current_password"
                                    placeholder="Current Password*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <input type="password" 
                                    v-model="data.new_password"
                                    placeholder="New Password*"
                                    class="form-control rounded-0">
                            </div>
                            <div class="mb-3">
                                <Spinner v-if="data.loading.updatingPassword" />
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
    import { useToast } from 'vue-toastification'
    import { getHeaderConfig } from "../../helpers/headerConfig"

    const toast = useToast()

    const store = useAuthStore()

    const data = reactive({
        name: '',
        new_password: '',
        current_password: '',
        errors: null,
        loading: {updatingName: false, updatingPassword: false}
    })

    const updateProfile = async () => {
        data.errors = null
        data.loading = {...data.loading, updatingName: true}
        const user = { name: data.name}
        try {
            const response = await axios.put(`${BASE_URL}/user/update/profile`, 
                user, getHeaderConfig(store.access_token))
            data.loading = {...data.loading, updatingName: false}
            store.setUser(response.data.user)
            data.name = ''
            toast.success('Your fullname has been updated', {
                timeout: 3000
            })
        } catch (error) {
            if(error?.response?.status === 422) {
                data.errors = error.response.data.errors
            }
            data.loading = {...data.loading, updatingName: false}
            console.log(error);
        }
    }

    const updatePassword = async () => {
        data.errors = null
        data.loading = {...data.loading, updatingPassword: true}
        const user = { current_password: data.current_password, password: data.new_password}
        try {
            const response = await axios.put(`${BASE_URL}/user/update/password`, 
                user, getHeaderConfig(store.access_token))
            data.loading = {...data.loading, updatingPassword: false}
            data.current_password = ''
            data.new_password = ''
            toast.success('Your password has been updated', {
                timeout: 3000
            })
        } catch (error) {
            if(error?.response?.status === 422) {
                data.errors = error.response.data.errors
            }
            data.loading = {...data.loading, updatingPassword: false}
            console.log(error);
        }
    }

</script>

<style>

</style>