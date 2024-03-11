<script setup lang="ts">
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router'
import { ElAvatar, ElBreadcrumb, ElButton, ElTabs, ElMessageBox } from 'element-plus';
import { onMounted, ref, watch, watchEffect } from 'vue';
import useTranslateStore from '../pinia/translate';
import useProfileStore from '../pinia/profile';
import { request } from '@/api/request';

const menus = ref([])
const router = useRouter()
const translateStore = useTranslateStore()
const profileStore = useProfileStore()

onMounted(() => {
    request.get('/menu.all').then(res => {
        menus.value = (res.data.data?.menus || []).filter(el => el.hidden_on_menu !== true).map(el => {
            if (el.children) {
                el.children = el.children.filter(el => el.hidden_on_menu !== true)
            }
            return el
        })
    })
    let token = localStorage.getItem('token')
    if (token) profileStore.refreshProfile()
})

/**
 * logout
 */
const logout = () => {
    // confirm 
    ElMessageBox.confirm('Are you sure to logout?', 'Logout', {
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel',
        type: 'warning',
    }).then(() => {
        request.post('/auth.logout').then(res => {
            location.href = '/'
            localStorage.removeItem('token')
        })
    }).catch(() => {
        // cancel
    });
}

const $t = trans

</script>

<template>
    <div class="min-h-screen">
        <el-container class="min-h-screen bg-gary-100">
            <div class="light:text-light-header-text light:bg-light-header-bg dark:bg-dark dark:text-white h-60px fixed w-full px-4 box-border border-solid border-0 border-b-1px border-gray-300"
                style="left: 0;top:0;z-index: 99;">
                <ElRow :align="'middle'" class="py-2 h-full">
                    <div class="flex-1">
                        <UIButton>
                            <span class="text-xl font-bold">{{ $t('AdminTitle') }}</span>
                        </UIButton>
                    </div>
                    <div class="flex flex-row items-center justify-center gap-4">
                        <UIButton class="flex-row-btn">
                            <Icon icon="ant-design:sort-ascending-outlined"></Icon>
                            <span>{{ translateStore.getLocaleToDisplay() }}</span>
                        </UIButton>
                        <UIButton class="flex-row-btn">
                            <Icon icon="ant-design:message-outlined"></Icon>
                            <span>{{ $t('header.notifyMessage') }}</span>
                        </UIButton>
                        <UIButton class="flex-row-btn">
                            <Icon icon="ant-design:setting-outlined"></Icon>
                            <span>{{ $t("setting") }}</span>
                        </UIButton>
                        <UIButton class="flex-row-btn" @click="logout">
                            <Icon icon="ant-design:login-outlined"></Icon>
                            <span>{{ profileStore.profile.name }}</span>
                        </UIButton>
                        <UIButton>
                            <ElAvatar :src="(profileStore.profile.avatar)" />
                        </UIButton>
                    </div>
                </ElRow>
            </div>
            <div style="height: 60px;" class="light:bg-gray-100"></div>
            <div class="flex flex-row flex-1 bg-gray-100">
                <div class="w-200px dark:bg-dark-800 light:bg-white fixed h-screen overflow-y-auto hidden-scroll-bar top-60px border-0 border-solid border-r-1px border-gray-200"
                    style="z-index: 98;">
                    <Menu :menus="menus" />
                    <div class="h-100px"></div>
                </div>
                <div class="w-200px"></div>
                <div class="flex-1 mt-60px dark:bg-dark-700">
                    <main class="p-4" style="width: calc(100vw - 232px);">
                        <RouterView :key="($route.meta.key as string)"></RouterView>
                    </main>
                </div>
            </div>
            <!-- <el-footer class="bg-dark-600">footer</el-footer> -->
        </el-container>
    </div>
</template>

<style scoped></style>
