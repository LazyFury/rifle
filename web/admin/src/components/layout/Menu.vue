<template>
    <div>
        <el-menu class="text-xl menus" @open="handleOpen" @close="handleClose" :default-active="defaultActive">
            <template v-for="(item, index) in menus">
                <SubMenu :item="item"></SubMenu>
                <el-menu-item v-if="!item.children || item.children.length <= 0" :index="item.key"
                    @click="to(item.path)">
                    <Icon :icon="item.icon"></Icon>
                    <span class="ml-1">{{ $t(item.title) }}</span>
                </el-menu-item>
            </template>
        </el-menu>
    </div>
</template>
<script>
import router from '@/router';
import SubMenu from './SubMenu.vue';
export default {
    components: {
        SubMenu
    },
    props: {
        menus: {
            type: Array,
            default: () => [
                {
                    title: '数据汇总',
                    icon: 'ant-design:dashboard-outlined',
                    path: '/',
                    key: 'overview'
                },
                {
                    title: '用户管理',
                    icon: 'iconoir:user-circle',
                    children: [
                        {
                            title: 'item one',
                            path: '/about',
                            key: "itemone"
                        },
                        {
                            title: 'item two',
                            path: '/about1',
                            key: "itemtwo"
                        }
                    ]
                },
                {
                    title: '关于 EVA Admin',
                    icon: 'iconoir:info-circle',
                    path: '/about',
                    key: "about"
                }
            ]
        }
    },
    data() {
        return {
            defaultActive: ""
        };
    },
    watch: {},
    computed: {

    },
    methods: {
        to(path) {
            router.push(path)
        },
        handleClose() {

        },
        handleOpen() {

        },
        findDefaultActive() {
            let href = window.location.href
            // path = hash last 
            let path = href.split('#').pop()
            // remove query
            path = path.split('?')[0]
            // path with out start / 
            path = path.replace(/^\//, '')

            // last path 
            path = path.split('/').pop()

            this.defaultActive = path
        }
    },
    created() { },
    mounted() {
        this.findDefaultActive()

        router.afterEach((to, from) => {
            this.findDefaultActive()
        })
    }
};
</script>
<style lang="scss" scoped>
.menus {
    .iconify {
        font-size: 16px !important;
    }
}
</style>
