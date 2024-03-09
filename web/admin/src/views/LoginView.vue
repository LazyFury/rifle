<template>
    <div>
        <div class="min-h-screen w-screen bg-dark text-white flex flex-col items-center justify-center">
            <ElCard class="w-420px min-h-360px">
                <div>
                    <h1 class="text-center text-2xl font-bold">Admin Login</h1>
                </div>

                <ElForm size="large" @submit.native.prevent="handleSubmit" :model="loginForm" :rules="loginFormRules"
                    ref="loginFormRef" label-position="top" class="mt-4">
                    <ElFormItem label="email" prop="email" key="email">
                        <ElInput v-model="loginForm.email" type="text" placeholder="email"></ElInput>
                    </ElFormItem>
                    <ElFormItem label="Password" prop="password" key="password">
                        <ElInput v-model="loginForm.password" show-password type="password" placeholder="Password">
                        </ElInput>
                    </ElFormItem>
                    <ElFormItem>
                        <ElButton type="primary" class="w-full" size="large" @click="submitLogin">Login</ElButton>
                    </ElFormItem>
                </ElForm>

                <ElDivider></ElDivider>

                <!-- buttons  -->
                <div class="flex flex-row justify-center gap-2">
                    <UIButton>
                        <div class="flex-row-btn">
                            <Icon icon="ant-design:github-outlined"></Icon>
                            <span>GitHub</span>
                        </div>
                    </UIButton>
                    <UIButton>
                        <div class="flex-row-btn">
                            <Icon icon="ant-design:google-outlined"></Icon>
                            <span>Google</span>
                        </div>
                    </UIButton>
                    <UIButton>
                        <div class="flex-row-btn">
                            <Icon icon="ant-design:facebook-outlined"></Icon>
                            <span>Facebook</span>
                        </div>
                    </UIButton>
                </div>
            </ElCard>
        </div>
    </div>
</template>

<script>
import { request } from "@/api/request";
export default {
    components: {},
    props: {},
    data() {
        return {
            loginForm: {
                email: "admin@mail.com",
                password: "123456"
            },
            loginFormRules: {
                email: [
                    { required: true, message: "Please input email", trigger: "blur" },
                    { type: "email", message: "Please input correct email", trigger: "blur" }
                ],
                password: [
                    { required: true, message: "Please input password", trigger: "blur" }
                ]
            }
        };
    },
    watch: {},
    computed: {},
    methods: {
        submitLogin() {
            this.$refs.loginFormRef.validate(valid => {
                if (valid) {
                    // this.$router.push({ path: '/dashboard' })
                    request.post('/auth.login', {
                        email: this.loginForm.email,
                        password: this.loginForm.password
                    }).then(res => {
                        if (res.data.code === 200) {
                            console.log(res.data.data)
                            let { token = "" } = res.data?.data || {}
                            localStorage.setItem('token', token)
                            // localStorage.setItem('expired_at',expired_at)
                            location.href = '/'
                        }
                    })
                }
            })
        },
        handleSubmit(e) {
            e.preventDefault()
            this.submitLogin()
        }
    },
    created() { },
    mounted() { }
};
</script>

<style lang="scss" scoped></style>