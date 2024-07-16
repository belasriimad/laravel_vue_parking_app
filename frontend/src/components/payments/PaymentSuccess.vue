<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <h4 class="text-center" v-if="hash === route.params.hash">
                        Payment done successfully
                    </h4>
                    <h4 class="text-center" v-else>
                        Your payment is pending!
                    </h4>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
    import { onMounted } from "vue"
    import { useRoute } from "vue-router"
    import { useAuthStore } from "../../stores/useAuthStore"



    const store = useAuthStore()
    const amount = JSON.parse(localStorage.getItem('amount')) || 0
    const hash = JSON.parse(localStorage.getItem('hash')) || null
    const route = useRoute()

    onMounted(() => {
        if(store.access_token && amount > 0 && hash === route.params.hash) {
            localStorage.removeItem('hash')
            localStorage.removeItem('amount')
        }
    })
</script>

<style>

</style>