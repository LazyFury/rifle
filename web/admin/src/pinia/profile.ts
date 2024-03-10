import { defineStore } from "pinia"
// @ts-ignore 
import { request } from '../api/request.js'


export const useProfileStore = defineStore({
    id: 'profile',
    state: () => ({
        profile: {
            id: 0,
            name: '',
            email: '',
            avatar: '',
            roles: [],
        },
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