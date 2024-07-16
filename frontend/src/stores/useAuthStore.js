import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        access_token: JSON.parse(localStorage.getItem('access_token')) || null
    }),
    getters: {},
    actions: {
        setUser(user) {
            this.user = user
        },
        setToken(token) {
            localStorage.setItem('access_token', JSON.stringify(token))
            this.access_token = token
        },
        clearStoredData() {
            localStorage.removeItem('access_token')
            this.access_token = null
            this.user = null
        }
    }
})