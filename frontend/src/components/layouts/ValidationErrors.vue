<template>
    <div class="row mb-2">
        <div class="col-md-8 mx-auto">
            <ul class="list-group mt-2" v-for="(validationErrors, index) in props.errors" 
                :key="index">
                <li class="list-group-item bg-danger text-white mb-1 rounded-0"
                    v-for="(error, index) in validationErrors" :key="index">
                    {{ error }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        errors: {
            type: Object,
            required: false,
            default: {}
        }
    })
</script>

<style>

</style>