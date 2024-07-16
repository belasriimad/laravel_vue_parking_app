<template>
    <div class="d-grid gap-2 col-6 mx-auto">
        <button class="btn btn-primary" @click="fetchPaymentUrl">
            Pay now
        </button>
    </div>
</template>

<script setup>
    import { BASE_URL } from "../../helpers/baseUrl"
    import { getHeaderConfig } from "../../helpers/headerConfig"
    import { useAuthStore } from "../../stores/useAuthStore"
    import axios from 'axios'

    const store = useAuthStore()

    const props = defineProps({
        amount: {
            type: Number,
            required: true
        }
    })

    const fetchPaymentUrl = async () => {
        try {
            const response = await axios.post(`${BASE_URL}/parking/pay`,
            {
                price: props.amount,
                success_url: `http://127.0.0.1:5173/#/payment/success/${createUniqueHash(20)}`
            }, getHeaderConfig(store.access_token))
            window.location.href = response.data.url
        } catch (error) {
            console.log(error);
        }
    }

    const createUniqueHash = (length) => {
        let hash = ''
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        const charactersLength = characters.length
        let counter = 0
        while (counter < length) {
            hash += characters.charAt(Math.floor(Math.random() * charactersLength))
            counter += 1
        }
        localStorage.setItem('hash', JSON.stringify(hash))
        return hash
    }

</script>

<style>

</style>