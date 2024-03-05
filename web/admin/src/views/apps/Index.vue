<template>
    <div>
        <!-- title  -->
        <h1 class="text-2xl font-bold mb-4">插件列表</h1>
        <ElDivider class="" />


        <div class="grid grid-cols-4">
            <ElCard v-for="plugin in plugins" :key="plugin.name" :header="plugin.label" shadow="hover" class="mb-4">
                <div class="flex flex-row">
                    <img :src="plugin.icon" class="w-8 h-8" />
                    <div class="ml-4">
                        <div>
                            <span class="name text-gray-600">{{ plugin.name }}</span>
                            <span class="text-gray-600">版本:</span>
                            <span class="text-gray-600">{{ plugin.version }}</span>
                        </div>
                        <p class="text-gray">{{ plugin.description }}</p>
                        <!-- url  -->
                        <div class="mt-2">
                            <ElLink type="primary" :underline="false" :href="plugin.link" target="_blank">查看详情</ElLink>
                        </div>
                        <!-- installed  -->
                        <!-- <div v-if="plugin.installed" class="mt-2">
                            <ElButton type="default" size="small">已安装</ElButton>
                        </div> -->
                        <!-- requirements use eltag  -->
                        <ElDivider class="!my-1 !mt-3" />
                        <h5 class="my-1">依赖：</h5>
                        <div v-if="plugin.requirements" class="mt-2 flex flex-row gap-1 flex-wrap">
                            <ElTag v-for="requirement in plugin.requirements" :key="requirement" type="success">{{ requirement }}</ElTag>
                        </div>
                    </div>
                </div>
            </ElCard>
        </div>
    </div>
</template>
<script>
import { request } from '@/api/request'
export default {
    components: {},
    props: {},
    data() {
        return {
            plugins: []
        };
    },
    watch: {},
    computed: {},
    methods: {},
    created() { },
    mounted() {
        request.get('/plugins').then(res => {
            this.plugins = res.data.data.plugins
        })
    }
};
</script>
<style lang="scss" scoped></style>