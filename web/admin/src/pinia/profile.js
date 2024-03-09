import { defineStore } from "pinia"
// @ts-ignore 
import { request } from '../api/request'

export const useProfileStore = defineStore({
    id: 'profile',
    state: () => ({
        profile: {},
    }),
    getters: {
        isLoggedIn: (state) => !!state.profile,
    },
    actions: {
        setProfile(profile) {
            this.profile = profile
        },
        refreshProfile() {
            request.get("/auth.profile").then(res => {
                this.profile = res.data?.data || {}
            })
        }
    },
})

export default useProfileStore