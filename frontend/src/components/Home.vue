<template>
    <div class="container">
        <div class="row my-4">
            <div class="col-md-8">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header bg-white text-center">
                        <h5 class="mt-2 fw-bold">
                            Sectors
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4" v-for="sector in data.sectors" :key="sector.id">
                                <div class="card border border-dark rounded-0 shadow-sm">
                                    <div class="card-header bg-white text-center">
                                        <h5 class="mt-3">
                                            {{ sector.name }} (${{ sector.hourly_price }}/h)
                                        </h5>
                                        <p class="text-muted">
                                            <span class="text-danger">*</span>
                                            <i>price for 1 hour</i>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="card border border-dark rounded-0 shadow-sm mb-2"
                                            v-for="place in sector.places" :key="place.id">
                                            <div class="card-header bg-white text-center">
                                                <h6 class="mt-3 text-capitalize">
                                                    {{ place.name }}
                                                </h6>
                                            </div>
                                            <div v-if="place.availlable" class="card-body d-flex flex-column justify-content-center align-items-center">
                                                <span class="mb-2">
                                                    <i class="bi bi-car-front-fill h1"></i>
                                                </span>
                                                <div v-if="data.loading.id === place.id && data.loading.status">
                                                    <Spinner /> 
                                                </div>
                                                <button 
                                                    v-else
                                                    @click="startParking(place.id)"
                                                    :disabled="data.amount > 0"
                                                    class="btn btn-sm btn-dark text-capitalize">
                                                    Park here
                                                </button>
                                            </div>
                                            <div v-else class="card-body d-flex flex-column justify-content-center align-items-center">
                                                <span class="badge bg-dark my-2">
                                                    {{ place.start_time }}
                                                </span>
                                                <span class="mb-2">
                                                    <i class="bi bi-ban h1 text-danger"></i>
                                                </span>
                                                <div v-if="data.loading.id === place.id && data.loading.status">
                                                    <Spinner /> 
                                                </div>
                                                <div v-else>
                                                    <button 
                                                        v-if="place.user_id === store.user.id"
                                                        @click="endParking(place.id)"
                                                        class="btn btn-sm btn-warning text-capitalize">
                                                        End parking
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" v-if="data.amount > 0">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header bg-white text-center">
                        <h5 class="mt-2">
                            Amount to pay: 
                            <span class="text-danger fw-bold mx-2">
                                ${{data.amount}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <Stripe :amount="data.amount" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, reactive } from "vue";
    import { BASE_URL } from "../helpers/baseUrl";
    import { getHeaderConfig } from "../helpers/headerConfig";
    import { useAuthStore } from "../stores/useAuthStore";
    import axios from 'axios'
    import { useToast } from 'vue-toastification'
    import Spinner from './layouts/Spinner.vue'
    import Stripe from './payments/Stripe.vue'

    const toast = useToast()

    const store = useAuthStore()    

    const data = reactive({
        sectors: [],
        loading: {id: null, status: false},
        amount: JSON.parse(localStorage.getItem('amount')) || 0
    })

    const fetchSectors = async () => {
        try {
            const response = await axios.get(`${BASE_URL}/sectors`, 
                getHeaderConfig(store.access_token))
            data.sectors = response.data.data
        } catch (error) {
            console.log(error)
        }
    }

    const startParking = async (place_id) => {
        data.loading = {id: place_id, status: true}
        const user = { user_id: store.user.id }
        try {
            const response = await axios.put(`${BASE_URL}/parking/${place_id}/start`,
            user,
            getHeaderConfig(store.access_token))

            data.loading = {id: place_id, status: false}
            if(response.data.error) {
                toast.error(response.data.error, {
                    timeout: 3000
                })
            }else {
                let updatedPlace = response.data.data
                const updatedSectors = data.sectors
                const sector = updatedSectors.find(sector => sector.id === updatedPlace.sector_id)
                sector.places = sector.places.map(place => place.id === place_id ?
                    {...place, ...updatedPlace} : place)
                data.sectors = updatedSectors
            }
        } catch (error) {
            data.loading = {id: place_id, status: false}
            console.log(error);
        }
    }

    const endParking = async (place_id) => {
        data.loading = {id: place_id, status: true}
        const user = { user_id: store.user.id }
        try {
            const response = await axios.put(`${BASE_URL}/parking/${place_id}/end`,
            user,
            getHeaderConfig(store.access_token))

            data.loading = {id: place_id, status: false}
            let updatedPlace = response.data.data
            const updatedSectors = data.sectors
            const sector = updatedSectors.find(sector => sector.id === updatedPlace.sector_id)
            sector.places = sector.places.map(place => place.id === place_id ?
                {...place, ...updatedPlace} : place)
            data.sectors = updatedSectors
            localStorage.setItem('amount', JSON.stringify(updatedPlace.total_price))
            data.amount = updatedPlace.total_price
            toast.success('Parking ended please check the amount to pay', {
                timeout: 3000
            })

        } catch (error) {
            data.loading = {id: place_id, status: false}
            console.log(error);
        }
    }

    onMounted(() => fetchSectors())
</script>

<style>

</style>